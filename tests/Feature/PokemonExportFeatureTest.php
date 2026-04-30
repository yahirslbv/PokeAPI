<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pokemon;

class PokemonExportFeatureTest extends TestCase
{
    use RefreshDatabase; // Limpia la BD en cada prueba

    // Autor: Victor Yahir Medrano Barrera
    public function test_export_valido_responde_200_y_json_valido()
    {
        $user = User::factory()->create();
        Pokemon::create([
            'user_id' => $user->id,
            'pokedex_number' => 25,
            'name' => 'Pikachu',
            'types' => ['electric'],
            'image' => 'img.png',
            'animated' => 'anim.gif',
            'species' => 'Ratón',
            'description' => 'Un ratón eléctrico',
            'height' => 0.4,
            'weight' => 6.0,
            'hp' => 35,
            'attack' => 55,
            'defense' => 40,
            'special_attack' => 50,
            'special_defense' => 50,
            'speed' => 90,
            'evolution_chain' => []
        ]);

        // 2. Actuar: USAMOS actingAs($user) para simular que iniciamos sesión
        $response = $this->actingAs($user)->get('/export/pokemon/Pikachu');

        // 3. Afirmar: Verificamos el código 200, el Header y el contenido JSON
        $response->assertStatus(200);
        
        // TÉCNICA ÚNICA DEL EQUIPO 5: Validar headers/content-type
        $response->assertHeader('Content-Type', 'application/json'); 
        
        $response->assertJson([
            'name' => 'Pikachu',
            'hp' => 35,
            'attack' => 55,
            'defense' => 40
        ]);
    }

    // Autor: Victor Yahir Medrano Barrera
    public function test_export_invalido_responde_controlado()
    {
        // 1. Preparar: Creamos un usuario
        $user = User::factory()->create();

        // 2. Actuar: Iniciamos sesión y buscamos un Pokémon que no existe
        $response = $this->actingAs($user)->get('/export/pokemon/Digimon');

        // 3. Afirmar: Debe devolver error 404 (No encontrado) pero en formato JSON
        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Pokémon no encontrado en la base local'
        ]);
    }
}