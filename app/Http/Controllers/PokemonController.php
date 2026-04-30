<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Pokemon;
use App\Services\PokeApiService; // Importamos el servicio OBLIGATORIO

class PokemonController extends Controller
{
    protected $pokeApi;

    // Inyección de dependencias: Laravel nos pasa el servicio automáticamente
    public function __construct(PokeApiService $pokeApi)
    {
        $this->pokeApi = $pokeApi;
    }

    public function index(Request $request)
    {
        $search = strtolower(trim($request->input('search')));
        $typeFilter = strtolower(trim($request->input('type')));

        try {
            // USAMOS EL SERVICIO EN LUGAR DE HTTP DIRECTO
            $allPokemons = Cache::remember('all_pokemon_names_v1', 86400, function () {
                $response = $this->pokeApi->getList(1500);
                return $response->successful() ? $response->json()['results'] : [];
            });
        } catch (\Exception $e) {
            $allPokemons = [];
        }

        $filteredList = $allPokemons;

        if ($typeFilter && !empty($filteredList)) {
            try {
                $typeData = Cache::remember("pokemon_type_{$typeFilter}", 86400, function () use ($typeFilter) {
                    // USAMOS EL SERVICIO
                    $response = $this->pokeApi->getType($typeFilter);
                    return $response->successful() ? $response->json()['pokemon'] : [];
                });
                $typeNames = array_map(fn($item) => $item['pokemon']['name'], $typeData);
                $filteredList = array_filter($filteredList, fn($item) => in_array($item['name'], $typeNames));
            } catch (\Exception $e) {
                // Falla silenciosa si no hay red para los filtros
            }
        }

        if ($search) {
            $filteredList = array_filter($filteredList, fn($item) => str_contains($item['name'], $search));
        }

        $resultsToFetch = array_slice($filteredList, 0, 20);
        $pokemons = [];

        foreach ($resultsToFetch as $item) {
            $id = basename($item['url']);
            try {
                $pokemonDetail = Cache::remember("pokemon_detail_v2_{$id}", 86400, function () use ($item) {
                    // USAMOS EL SERVICIO
                    $detailResponse = $this->pokeApi->getFromUrl($item['url']);
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
            } catch (\Exception $e) {
                continue;
            }
        }

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
        
        try {
            // USAMOS EL SERVICIO
            $response = $this->pokeApi->getPokemon($name);

            if ($response->failed()) {
                return view('pokemon.error', ['name' => $name]);
            }

            $data = $response->json();
            $speciesResponse = $this->pokeApi->getFromUrl($data['species']['url']);
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

                $evolutionResponse = $this->pokeApi->getFromUrl($speciesData['evolution_chain']['url']);
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

        } catch (\Exception $e) {
            $pokemonLocal = Pokemon::where('name', ucfirst($name))->first();

            if ($pokemonLocal) {
                $pokemon = [
                    'pokedex_number' => $pokemonLocal->pokedex_number,
                    'name' => $pokemonLocal->name,
                    'species' => $pokemonLocal->species ?? 'Pokémon',
                    'image' => $pokemonLocal->image,
                    'animated' => $pokemonLocal->animated,
                    'types' => is_array($pokemonLocal->types) ? $pokemonLocal->types : json_decode($pokemonLocal->types, true) ?? ['desconocido'],
                    'descripcion' => ($pokemonLocal->description ?? 'Sin descripción') . " (Modo Offline)",
                    'height' => $pokemonLocal->height ?? 0,
                    'weight' => $pokemonLocal->weight ?? 0,
                    'stats' => [
                        'hp' => $pokemonLocal->hp,
                        'attack' => $pokemonLocal->attack,
                        'defense' => $pokemonLocal->defense,
                        'special-attack' => $pokemonLocal->special_attack ?? 0,
                        'special-defense' => $pokemonLocal->special_defense ?? 0,
                        'speed' => $pokemonLocal->speed ?? 0,
                        'total' => $pokemonLocal->hp + $pokemonLocal->attack + $pokemonLocal->defense + ($pokemonLocal->special_attack ?? 0) + ($pokemonLocal->special_defense ?? 0) + ($pokemonLocal->speed ?? 0)
                    ],
                    'evoluciones' => is_array($pokemonLocal->evolution_chain) ? $pokemonLocal->evolution_chain : json_decode($pokemonLocal->evolution_chain, true) ?? []
                ];

                $esFavorito = Auth::check() && $pokemonLocal->user_id === Auth::id();
                
                return view('pokemon.show', compact('pokemon', 'esFavorito'));
            }

            return view('pokemon.error', ['name' => $name]);
        }
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

        try {
            // USAMOS EL SERVICIO
            $data = $this->pokeApi->getPokemon($name)->json();
            $id = $data['id'];

            $speciesResponse = $this->pokeApi->getFromUrl($data['species']['url'])->json();
            $desc = collect($speciesResponse['flavor_text_entries'])->firstWhere('language.name', 'es')['flavor_text'] ?? 'Sin descripción';
            $genus = collect($speciesResponse['genera'])->firstWhere('language.name', 'es')['genus'] ?? 'Pokémon';
            
            $evoResponse = $this->pokeApi->getFromUrl($speciesResponse['evolution_chain']['url'])->json();
            $evoluciones = $this->parseEvolutionChain($evoResponse['chain']);

            $imgCont = $this->pokeApi->getFromUrl($data['sprites']['front_default'])->body();
            $animUrl = $data['sprites']['other']['showdown']['front_default'] ?? $data['sprites']['front_default'];
            $animCont = $this->pokeApi->getFromUrl($animUrl)->body();

            Storage::disk('public')->put("pokemon/{$id}.png", $imgCont);
            Storage::disk('public')->put("pokemon/{$id}.gif", $animCont);

            Pokemon::create([
                'user_id' => $userId,
                'pokedex_number' => $id,
                'name' => $name,
                'types' => array_map(fn($t) => $t['type']['name'], $data['types']),
                'image' => "/storage/pokemon/{$id}.png",
                'animated' => "/storage/pokemon/{$id}.gif",
                'species' => $genus,
                'description' => str_replace(["\n", "\f", "\r"], " ", $desc),
                'height' => $data['height'] / 10,
                'weight' => $data['weight'] / 10,
                'hp' => $data['stats'][0]['base_stat'],
                'attack' => $data['stats'][1]['base_stat'],
                'defense' => $data['stats'][2]['base_stat'],
                'special_attack' => $data['stats'][3]['base_stat'],
                'special_defense' => $data['stats'][4]['base_stat'],
                'speed' => $data['stats'][5]['base_stat'],
                'evolution_chain' => $evoluciones
            ]);

            return back()->with('success', 'Guardado en favoritos locales.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error de conexión: No se pudo guardar el Pokémon para modo offline.');
        }
    }

    public function favorites()
    {
        $pokemons = Pokemon::where('user_id', Auth::id())->get()->toArray();
        return view('pokemon.favorites', compact('pokemons'));
    }

    // MISIÓN EQUIPO 5: Exportar JSON
    public function export($name)
    {
        $pokemon = Pokemon::where('name', ucfirst($name))->orWhere('name', strtolower($name))->first();

        if (!$pokemon) {
            return response()->json([
                'success' => false,
                'message' => 'Pokémon no encontrado en la base local'
            ], 404);
        }

        return response()->json(
            $pokemon->toExportFormat(), 
            200, 
            ['Content-Type' => 'application/json']
        );
    }
}