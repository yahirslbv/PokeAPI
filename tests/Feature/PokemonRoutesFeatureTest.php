<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PokemonRoutesFeatureTest extends TestCase
{
    use RefreshDatabase;
    public function test_ruta_home_carga_correctamente_estado_200()
    {
        // La ruta principal '/' suele ser pública
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_ruta_about_carga_correctamente_estado_200()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/about');
        $response->assertStatus(200);
    }

    public function test_rutas_protegidas_redirigen_al_login_si_no_hay_sesion()
    {
        // Validamos la seguridad del sistema: intentar ver favoritos sin login
        $response = $this->get('/favoritos');
        
        // 302 es el código de redirección (nos manda a iniciar sesión)
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}