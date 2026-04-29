<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $error = null;

        // Si la BD está vacía, nos conectamos a internet UNA SOLA VEZ para descargar todo
        if (Pokemon::count() == 0) {
            $response = Http::withoutVerifying()->get('https://pokeapi.co/api/v2/pokemon?limit=20');
            $results = $response->successful() ? $response->json()['results'] : [];

            $pokemonsParaJson = []; // Arreglo para almacenar datos y enviarlos al JSON

            foreach ($results as $item) {
                $detailResponse = Http::withoutVerifying()->get($item['url']);
                
                if ($detailResponse->successful()) {
                    $data = $detailResponse->json();
                    $id = $data['id'];
                    $name = ucfirst($data['name']);
                    $type = $data['types'][0]['type']['name'];

                    // 1. Extraer Estadísticas
                    $hp = 0; $attack = 0; $defense = 0;
                    foreach ($data['stats'] as $stat) {
                        if ($stat['stat']['name'] == 'hp') $hp = $stat['base_stat'];
                        if ($stat['stat']['name'] == 'attack') $attack = $stat['base_stat'];
                        if ($stat['stat']['name'] == 'defense') $defense = $stat['base_stat'];
                    }

                    // 2. Descargar las imágenes a la computadora
                    $imageUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png";
                    $animatedUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/{$id}.gif";

                    $imageContents = Http::withoutVerifying()->get($imageUrl)->body();
                    
                    // Si falla la animada, usamos la estática de respaldo
                    $animatedResponse = Http::withoutVerifying()->get($animatedUrl);
                    $animatedContents = $animatedResponse->successful() ? $animatedResponse->body() : $imageContents;

                    // 3. Guardar físicamente en storage/app/public/pokemon/
                    $imagePath = "pokemon/{$id}.png";
                    $animatedPath = "pokemon/{$id}.gif";
                    Storage::disk('public')->put($imagePath, $imageContents);
                    Storage::disk('public')->put($animatedPath, $animatedContents);

                    // 4. Guardar en Base de Datos Local
                    $nuevoPokemon = Pokemon::create([
                        'pokedex_number' => $id, // <-- Asignación del número de Pokédex
                        'name' => $name,
                        'type' => $type,
                        'image' => "/storage/" . $imagePath,
                        'animated' => "/storage/" . $animatedPath,
                        'hp' => $hp,
                        'attack' => $attack,
                        'defense' => $defense
                    ]);

                    // 5. Agregar al arreglo del JSON
                    $pokemonsParaJson[] = $nuevoPokemon->toArray();
                }
            }

            // 6. Generar el archivo JSON localmente
            Storage::disk('public')->put('pokemons.json', json_encode($pokemonsParaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        // Lógica del buscador
        if ($request->has('search')) {
            // Se convierte a minúsculas porque la PokeAPI exige el formato en minúsculas
            $busqueda = strtolower(trim($request->input('search')));

            if (empty($busqueda)) {
                $error = 'Por favor, ingresa un nombre para buscar.';
                $pokemons = Pokemon::all()->toArray(); 
            } else {
                // 1. Intentar buscar en la base de datos local primero
                $pokemons = Pokemon::where('name', 'LIKE', '%' . $busqueda . '%')->get()->toArray();

                // 2. Si no hay resultados locales, hacemos la petición a la PokeAPI
                if (empty($pokemons)) {
                    $detailResponse = Http::withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/{$busqueda}");

                    if ($detailResponse->successful()) {
                        $data = $detailResponse->json();
                        $id = $data['id'];
                        $name = ucfirst($data['name']);
                        
                        // Validar que el Pokémon tenga tipos antes de extraerlos
                        $type = isset($data['types'][0]['type']['name']) ? $data['types'][0]['type']['name'] : 'normal';

                        // Extraer Estadísticas
                        $hp = 0; $attack = 0; $defense = 0;
                        foreach ($data['stats'] as $stat) {
                            if ($stat['stat']['name'] == 'hp') $hp = $stat['base_stat'];
                            if ($stat['stat']['name'] == 'attack') $attack = $stat['base_stat'];
                            if ($stat['stat']['name'] == 'defense') $defense = $stat['base_stat'];
                        }

                        // Descargar las imágenes a la computadora
                        $imageUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png";
                        $animatedUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/{$id}.gif";

                        $imageContents = Http::withoutVerifying()->get($imageUrl)->body();
                        
                        $animatedResponse = Http::withoutVerifying()->get($animatedUrl);
                        $animatedContents = $animatedResponse->successful() ? $animatedResponse->body() : $imageContents;

                        // Guardar físicamente
                        $imagePath = "pokemon/{$id}.png";
                        $animatedPath = "pokemon/{$id}.gif";
                        Storage::disk('public')->put($imagePath, $imageContents);
                        Storage::disk('public')->put($animatedPath, $animatedContents);

                        // Guardar en Base de Datos Local
                        $nuevoPokemon = Pokemon::create([
                            'pokedex_number' => $id, // <-- Asignación del número de Pokédex
                            'name' => $name,
                            'type' => $type,
                            'image' => "/storage/" . $imagePath,
                            'animated' => "/storage/" . $animatedPath,
                            'hp' => $hp,
                            'attack' => $attack,
                            'defense' => $defense
                        ]);

                        // Asignar el nuevo Pokémon para mostrarlo en la vista
                        $pokemons = [$nuevoPokemon->toArray()];

                        // Opcional: Actualizar el archivo JSON para incluir el nuevo registro
                        $todosLosPokemons = Pokemon::all()->toArray();
                        Storage::disk('public')->put('pokemons.json', json_encode($todosLosPokemons, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                    } else {
                        // Si la API devuelve error (ej. nombre incorrecto)
                        $error = 'No se encontró ningún Pokémon con ese nombre.';
                    }
                }
            }
        } else {
            $pokemons = Pokemon::all()->toArray();
        }

        return view('pokemon.index', [
            'pokemons' => $pokemons,
            'error' => $error
        ]);
    }

    public function show($name)
    {
        // AHORA LEE LOCALMENTE: Busca en la base de datos en lugar de la API
        $pokemonModel = Pokemon::where('name', ucfirst($name))->orWhere('name', strtolower($name))->first();

        if (!$pokemonModel) {
            return view('pokemon.error', ['name' => $name]);
        }

        // Preparamos los datos con la misma estructura que esperaba tu vista original
        $pokemon = [
            'pokedex_number' => $pokemonModel->pokedex_number, // <-- Se agrega para disponibilidad en la vista
            'name' => $pokemonModel->name,
            'image' => $pokemonModel->image,
            'animated' => $pokemonModel->animated,
            'types' => [$pokemonModel->type], 
            'stats' => [
                'hp' => $pokemonModel->hp,
                'attack' => $pokemonModel->attack,
                'defense' => $pokemonModel->defense
            ]
        ];

        return view('pokemon.show', ['pokemon' => $pokemon]);
    }
}