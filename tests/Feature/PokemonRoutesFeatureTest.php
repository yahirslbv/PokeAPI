<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http; // Importante para simular la API (Fake)

class PokemonRoutesFeatureTest extends TestCase
{
    use RefreshDatabase;

    // 1. GET / responde 200 (Home).
    public function test_ruta_home_carga_correctamente_estado_200()
    {
        // La ruta principal '/' suele ser pública
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    // 3. GET /about responde 200 (Acerca de).
    public function test_ruta_about_carga_correctamente_estado_200()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/about');
        $response->assertStatus(200);
    }
    // Prueba de seguridad de rutas protegidas
    public function test_rutas_protegidas_redirigen_al_login_si_no_hay_sesion()
    {
        // Validamos la seguridad del sistema: intentar ver favoritos sin login
        $response = $this->get('/favoritos');
        
        // 302 es el código de redirección (nos manda a iniciar sesión)
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // 2. GET /pokemon responde 200 (Listado)
    public function test_get_pokemon_responde_200_listado()
    {
        $user = User::factory()->create(); // Creamos usuario
        Http::fake(); // Fake obligatorio para no usar internet
        
        $response = $this->actingAs($user)->get('/pokemon'); // Iniciamos sesión virtual
        $response->assertStatus(200);
    }

    // 4. GET /pokemon/pikachu (nombre válido) responde 200
    public function test_get_pokemon_valido_responde_200()
    {
        $user = User::factory()->create();
        Http::fake([
            'pokeapi.co/api/v2/pokemon/pikachu' => Http::response(['id' => 25, 'name' => 'pikachu', 'types' => [], 'stats' => [], 'sprites' => ['other' => []]], 200),
            '*' => Http::response([], 200)
        ]);
        
        $response = $this->actingAs($user)->get('/pokemon/pikachu');
        $response->assertStatus(200);
    }

    // 5. GET /pokemon/nombreinvalido responde de forma controlada (vista amigable)
    public function test_get_pokemon_invalido_responde_controlado()
    {
        $user = User::factory()->create();
        Http::fake([
            '*' => Http::response(null, 404) // Simulamos que no existe en la API
        ]);
        
        $response = $this->actingAs($user)->get('/pokemon/digimon');
        
        // Da 200 porque carga nuestra vista 'pokemon.error' amigable, NO hace crash la app
        $response->assertStatus(200);
    }

    // 6. Buscador vacío en /pokemon muestra validación (mensaje de error)
    public function test_buscador_vacio_en_pokemon_muestra_validacion()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/pokemon?search=');
        
        // Verificamos que falle la validación y redirija con el error en el campo 'search'
        $response->assertSessionHasErrors('search');
    }
}