<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Pokemon;

class PokemonExportTest extends TestCase
{
    // Autor: Victor Yahir Medrano Barrera
    public function test_to_export_format_genera_json_correcto()
    {
        $pokemon = new Pokemon([
            'name' => 'Pikachu',
            'types' => ['electric'],
            'hp' => 35,
            'attack' => 55,
            'defense' => 40
        ]);

        $exported = $pokemon->toExportFormat();

        $this->assertEquals('Pikachu', $exported['name']);
        $this->assertEquals(['electric'], $exported['types']);
        $this->assertEquals(35, $exported['hp']);
    }

    // Autor: Victor Yahir Medrano Barrera
    public function test_si_falta_un_campo_export_no_rompe()
    {
        // Simulamos un Pokémon incompleto
        $pokemon = new Pokemon([
            'name' => 'Bulbasaur'
            // Faltan tipos, hp, attack y defense
        ]);

        $exported = $pokemon->toExportFormat();

        // Validamos que se controló el error asignando valores por defecto
        $this->assertEquals('Bulbasaur', $exported['name']);
        $this->assertEquals([], $exported['types']); 
        $this->assertEquals(0, $exported['hp']); 
        $this->assertEquals(0, $exported['attack']); 
    }
}