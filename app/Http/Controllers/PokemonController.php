<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $search = strtolower(trim($request->input('search')));
        $typeFilter = strtolower(trim($request->input('type')));

        // 1. Obtener la lista maestra de nombres
        $allPokemons = Cache::remember('all_pokemon_names_v1', 86400, function () {
            $response = Http::withoutVerifying()->get('https://pokeapi.co/api/v2/pokemon?limit=1500');
            return $response->successful() ? $response->json()['results'] : [];
        });

        $filteredList = $allPokemons;

        // 2. Filtro por Tipo
        if ($typeFilter) {
            $typeData = Cache::remember("pokemon_type_{$typeFilter}", 86400, function () use ($typeFilter) {
                $response = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/type/{$typeFilter}");
                return $response->successful() ? $response->json()['pokemon'] : [];
            });

            $typeNames = array_map(fn($item) => $item['pokemon']['name'], $typeData);
            $filteredList = array_filter($filteredList, fn($item) => in_array($item['name'], $typeNames));
        }

        // 3. Filtro por Búsqueda
        if ($search) {
            $filteredList = array_filter($filteredList, fn($item) => str_contains($item['name'], $search));
        }

        $resultsToFetch = array_slice($filteredList, 0, 20);

        // 4. Obtener detalles con múltiples tipos
        $pokemons = [];
        foreach ($resultsToFetch as $item) {
            $urlParts = explode('/', rtrim($item['url'], '/'));
            $id = end($urlParts);

            // Caché v2 para soportar el arreglo de tipos
            $pokemonDetail = Cache::remember("pokemon_detail_v2_{$id}", 86400, function () use ($item) {
                $detailResponse = Http::withoutVerifying()->get($item['url']);
                return $detailResponse->successful() ? $detailResponse->json() : null;
            });

            if ($pokemonDetail) {
                $pokemons[] = [
                    'pokedex_number' => $id,
                    'name' => ucfirst($pokemonDetail['name']),
                    'types' => array_map(fn($t) => $t['type']['name'], $pokemonDetail['types']),
                    'image' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png",
                    'animated' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/{$id}.gif"
                ];
            }
        }

        // 5. Marcar cuáles son favoritos del usuario actual para la vista del catálogo
        $favNames = Auth::check() ? Pokemon::where('user_id', Auth::id())->pluck('name')->toArray() : [];
        foreach ($pokemons as $key => $p) {
            $pokemons[$key]['is_favorite'] = in_array($p['name'], $favNames);
        }

        $tipos = ['normal', 'fire', 'water', 'electric', 'grass', 'ice', 'fighting', 'poison', 'ground', 'flying', 'psychic', 'bug', 'rock', 'ghost', 'dragon', 'dark', 'steel', 'fairy'];

        return view('pokemon.index', compact('pokemons', 'tipos'));
    }

    public function show($name)
    {
        $name = strtolower($name);
        $response = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/" . $name);

        if ($response->failed()) {
            return view('pokemon.error', ['name' => $name]);
        }

        $data = $response->json();
        $speciesResponse = Http::withoutVerifying()->get($data['species']['url']);
        $speciesData = $speciesResponse->successful() ? $speciesResponse->json() : null;

        $descripcion = 'No hay descripción disponible.';
        $genus = 'Pokémon';
        $evoluciones = [];

        if ($speciesData) {
            $esEntry = collect($speciesData['flavor_text_entries'])->firstWhere('language.name', 'es');
            if ($esEntry) {
                $descripcion = str_replace(["\n", "\f", "\r"], " ", $esEntry['flavor_text']);
            }
            $esGenus = collect($speciesData['genera'])->firstWhere('language.name', 'es');
            if ($esGenus) { $genus = $esGenus['genus']; }

            $evolutionResponse = Http::withoutVerifying()->get($speciesData['evolution_chain']['url']);
            if ($evolutionResponse->successful()) {
                $evoluciones = $this->parseEvolutionChain($evolutionResponse->json()['chain']);
            }
        }

        $stats = [];
        foreach ($data['stats'] as $stat) {
            $stats[$stat['stat']['name']] = $stat['base_stat'];
        }
        $stats['total'] = array_sum(array_values($stats));

        $pokemon = [
            'pokedex_number' => $data['id'],
            'name' => ucfirst($data['name']),
            'species' => $genus,
            'image' => $data['sprites']['front_default'],
            'animated' => $data['sprites']['other']['showdown']['front_default'] ?? $data['sprites']['front_default'],
            'types' => array_map(fn($t) => $t['type']['name'], $data['types']),
            'descripcion' => $descripcion,
            'height' => $data['height'] / 10,
            'weight' => $data['weight'] / 10,
            'stats' => $stats,
            'evoluciones' => $evoluciones
        ];

        $esFavorito = Auth::check() && Pokemon::where('user_id', Auth::id())->where('name', $pokemon['name'])->exists();
        
        return view('pokemon.show', compact('pokemon', 'esFavorito'));
    }

    private function parseEvolutionChain($chain)
    {
        $evolutions = [];
        $current = $chain;
        do {
            $speciesUrl = $current['species']['url'];
            $id = basename($speciesUrl);
            $evolutions[] = [
                'id' => $id,
                'name' => ucfirst($current['species']['name']),
                'sprite' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png"
            ];
            $current = $current['evolves_to'][0] ?? null;
        } while ($current);
        return $evolutions;
    }

    public function toggleFavorite(Request $request)
    {
        $name = ucfirst($request->name);
        $userId = Auth::id();
        $fav = Pokemon::where('user_id', $userId)->where('name', $name)->first();

        if ($fav) {
            $fav->delete();
            return back()->with('success', 'Eliminado de favoritos.');
        }

        $data = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/".strtolower($name))->json();
        $id = $data['id'];

        $imgCont = Http::withoutVerifying()->get($data['sprites']['front_default'])->body();
        $animUrl = $data['sprites']['other']['showdown']['front_default'] ?? $data['sprites']['front_default'];
        $animCont = Http::withoutVerifying()->get($animUrl)->body();

        Storage::disk('public')->put("pokemon/{$id}.png", $imgCont);
        Storage::disk('public')->put("pokemon/{$id}.gif", $animCont);

        Pokemon::create([
            'user_id' => $userId,
            'pokedex_number' => $id,
            'name' => $name,
            'type' => $data['types'][0]['type']['name'],
            'image' => "/storage/pokemon/{$id}.png",
            'animated' => "/storage/pokemon/{$id}.gif",
            'hp' => $data['stats'][0]['base_stat'],
            'attack' => $data['stats'][1]['base_stat'],
            'defense' => $data['stats'][2]['base_stat'],
        ]);

        return back()->with('success', 'Guardado en favoritos locales.');
    }

    public function favorites()
    {
        // Se utiliza toArray() para que sea consistente con la vista index
        $pokemons = Pokemon::where('user_id', Auth::id())->get()->toArray();
        return view('pokemon.favorites', compact('pokemons'));
    }
}