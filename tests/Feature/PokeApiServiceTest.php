<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\PokeApiService;

class PokeApiServiceTest extends TestCase
{
    // Autor: Victor Yahir Medrano Barrera
    public function test_servicio_pokeapi_devuelve_datos_correctos_sin_internet()
    {
        // TÉCNICA OBLIGATORIA: Simulamos la respuesta de la API (Mocking)
        Http::fake([
            'pokeapi.co/*' => Http::response(['name' => 'pikachu', 'id' => 25], 200)
        ]);

        $service = new PokeApiService();
        $response = $service->getPokemon('pikachu');

        // Validamos que el servicio funciona simulando la conexión
        $this->assertEquals(200, $response->status());
        $this->assertEquals('pikachu', $response->json()['name']);
    }

    // Autor: Victor Yahir Medrano Barrera
    public function test_servicio_pokeapi_maneja_error_404_correctamente()
    {
        // TÉCNICA OBLIGATORIA: Simulamos que la API falla o el Pokémon no existe
        Http::fake([
            'pokeapi.co/*' => Http::response(null, 404)
        ]);

        $service = new PokeApiService();
        $response = $service->getPokemon('agumon'); // Nombre inválido

        $this->assertEquals(404, $response->status());
    }
}