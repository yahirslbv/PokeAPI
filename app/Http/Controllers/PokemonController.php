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
        if ($request->has('search') && !empty($request->search)) {
            return redirect()->route('pokemon.show', ['name' => strtolower(trim($request->search))]);
        }

        $pokemons = Cache::remember('api_pokemons_list_v3', 86400, function () {
            $response = Http::withoutVerifying()->get('https://pokeapi.co/api/v2/pokemon?limit=20');
            $results = $response->successful() ? $response->json()['results'] : [];
            $temp = [];

            foreach ($results as $item) {
                $id = basename($item['url']);
                $detail = Http::withoutVerifying()->get($item['url'])->json();
                $temp[] = [
                    'pokedex_number' => $id,
                    'name' => ucfirst($item['name']),
                    'type' => $detail['types'][0]['type']['name'],
                    'image' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png",
                    'animated' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/{$id}.gif"
                ];
            }
            return $temp;
        });

        return view('pokemon.index', compact('pokemons'));
    }

    public function show($name)
    {
        $response = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/" . strtolower($name));
        if ($response->failed()) return view('pokemon.error', ['name' => $name]);

        $data = $response->json();
        $pokemon = [
            'pokedex_number' => $data['id'],
            'name' => ucfirst($data['name']),
            'image' => $data['sprites']['front_default'],
            'animated' => $data['sprites']['other']['showdown']['front_default'] ?? $data['sprites']['front_default'],
            'types' => array_map(fn($t) => $t['type']['name'], $data['types']),
            'stats' => ['hp' => $data['stats'][0]['base_stat'], 'attack' => $data['stats'][1]['base_stat'], 'defense' => $data['stats'][2]['base_stat']]
        ];

        $esFavorito = Auth::check() && Pokemon::where('user_id', Auth::id())->where('name', $pokemon['name'])->exists();
        return view('pokemon.show', compact('pokemon', 'esFavorito'));
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

        // Descarga local de imágenes
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
        $pokemons = Pokemon::where('user_id', Auth::id())->get();
        return view('pokemon.favorites', compact('pokemons'));
    }
}