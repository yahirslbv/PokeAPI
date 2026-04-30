<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\PokemonMapper;

class PokemonMapperTest extends TestCase
{
    // Autor: Victor Yahir Medrano Barrera

    // 1. El mapper devuelve un arreglo con las llaves esperadas
    public function test_mapper_devuelve_arreglo_con_llaves_esperadas()
    {
        $apiData = ['id' => 1, 'name' => 'Bulbasaur', 'stats' => [], 'types' => [], 'sprites' => []];
        $result = PokemonMapper::map($apiData);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('sprite', $result);
    }

    // 2. Extrae tipos correctamente cuando hay 1 tipo
    public function test_extrae_tipos_cuando_hay_un_tipo()
    {
        $apiData = [
            'id' => 4, 'name' => 'Charmander',
            'types' => [ ['type' => ['name' => 'fire']] ]
        ];
        $result = PokemonMapper::map($apiData);
        $this->assertEquals(['fire'], $result['types']);
    }

    // 3. Extrae tipos correctamente cuando hay 2 tipos
    public function test_extrae_tipos_cuando_hay_dos_tipos()
    {
        $apiData = [
            'id' => 1, 'name' => 'Bulbasaur',
            'types' => [ ['type' => ['name' => 'grass']], ['type' => ['name' => 'poison']] ]
        ];
        $result = PokemonMapper::map($apiData);
        $this->assertEquals(['grass', 'poison'], $result['types']);
    }

    // 4. Extrae stats hp/attack/defense correctamente
    public function test_extrae_stats_correctamente()
    {
        $apiData = [
            'id' => 25, 'name' => 'Pikachu',
            'stats' => [
                ['stat' => ['name' => 'hp'], 'base_stat' => 35],
                ['stat' => ['name' => 'attack'], 'base_stat' => 55],
                ['stat' => ['name' => 'defense'], 'base_stat' => 40]
            ]
        ];
        $result = PokemonMapper::map($apiData);
        $this->assertEquals(35, $result['hp']);
        $this->assertEquals(55, $result['attack']);
        $this->assertEquals(40, $result['defense']);
    }

    // 5. Maneja respuesta incompleta sin romper
    public function test_maneja_respuesta_incompleta_sin_romper()
    {
        $apiData = ['name' => 'FaltaID']; // Falta el ID
        $result = PokemonMapper::map($apiData);
        $this->assertEquals('Desconocido', $result['name']);
        $this->assertEquals(0, $result['hp']);
    }

    // 6. Maneja respuesta vacía sin romper
    public function test_maneja_respuesta_vacia_sin_romper()
    {
        $result = PokemonMapper::map([]);
        $this->assertNull($result);
    }

    // 7. Normalización de nombre (trim/lower)
    public function test_normaliza_el_nombre_correctamente()
    {
        $apiData = ['id' => 150, 'name' => '  MEWTWO  '];
        $result = PokemonMapper::map($apiData);
        $this->assertEquals('mewtwo', $result['name']);
    }

    // 8. Manejo de "pokemon no encontrado"
    public function test_maneja_pokemon_no_encontrado()
    {
        $apiData = ['detail' => 'Not found'];
        $result = PokemonMapper::map($apiData);
        $this->assertNull($result);
    }
}