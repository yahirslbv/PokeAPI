<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // Esta línea es vital para que no marque error

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $error = null;

        // Se usa la nueva memoria 'pokemons_list_v2' para tener la carga rápida
        $pokemons = Cache::remember('pokemons_list_v2', 86400, function () {
            
            $response = Http::withoutVerifying()->get('https://pokeapi.co/api/v2/pokemon?limit=20');
            $results = $response->successful() ? $response->json()['results'] : [];
            $tempPokemons = [];

            foreach ($results as $item) {
                $urlParts = explode('/', rtrim($item['url'], '/'));
                $id = end($urlParts);

                $detailResponse = Http::withoutVerifying()->get($item['url']);
                $type = 'normal';
                if ($detailResponse->successful()) {
                    $type = $detailResponse->json()['types'][0]['type']['name'];
                }

                $tempPokemons[] = [
                    'id' => $id,
                    'name' => ucfirst($item['name']),
                    'type' => $type,
                    'image' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png",
                    'animated' => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/{$id}.gif"
                ];
            }
            
            return $tempPokemons;
        });

        // Lógica del buscador
        if ($request->has('search')) {
            $busqueda = trim($request->input('search'));

            if (empty($busqueda)) {
                $error = 'Por favor, ingresa un nombre para buscar.';
            } else {
                $pokemons = array_filter($pokemons, function ($pokemon) use ($busqueda) {
                    return stripos($pokemon['name'], $busqueda) !== false;
                });
            }
        }

        return view('pokemon.index', [
            'pokemons' => $pokemons,
            'error' => $error
        ]);
    }

    public function show($name)
    {
        $response = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/" . strtolower($name));

        if ($response->failed()) {
            return view('pokemon.error', ['name' => $name]);
        }

        $data = $response->json();

        $stats = [];
        foreach ($data['stats'] as $stat) {
            if (in_array($stat['stat']['name'], ['hp', 'attack', 'defense'])) {
                $stats[$stat['stat']['name']] = $stat['base_stat'];
            }
        }

        $pokemon = [
            'name' => ucfirst($data['name']),
            'image' => $data['sprites']['front_default'],
            'animated' => $data['sprites']['other']['showdown']['front_default'] ?? $data['sprites']['front_default'],
            'types' => array_map(function($type) { return $type['type']['name']; }, $data['types']),
            'stats' => $stats
        ];

        return view('pokemon.show', ['pokemon' => $pokemon]);
    }
}