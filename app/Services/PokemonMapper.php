<?php

namespace App\Services;

class PokemonMapper
{
    public static function map($apiData)
    {
        // 6. Maneja respuesta vacía sin romper
        if (empty($apiData)) {
            return null; 
        }

        // 8. Manejo de "pokemon no encontrado" (respuesta 404 controlada)
        if (isset($apiData['detail']) && $apiData['detail'] === 'Not found') {
            return null;
        }

        // 5. Maneja respuesta incompleta sin romper
        if (!isset($apiData['id'], $apiData['name'])) {
            return [
                'name' => 'Desconocido',
                'types' => [],
                'hp' => 0,
                'attack' => 0,
                'defense' => 0,
                'sprite' => null
            ];
        }

        $stats = [];
        if (isset($apiData['stats'])) {
            foreach ($apiData['stats'] as $stat) {
                $stats[$stat['stat']['name']] = $stat['base_stat'];
            }
        }

        $types = [];
        if (isset($apiData['types'])) {
            foreach ($apiData['types'] as $typeInfo) {
                $types[] = $typeInfo['type']['name'];
            }
        }

        return [
            // 7. Normalización de nombre (trim/lower)
            'name' => strtolower(trim($apiData['name'])), 
            'types' => $types,
            'hp' => $stats['hp'] ?? 0,
            'attack' => $stats['attack'] ?? 0,
            'defense' => $stats['defense'] ?? 0,
            'sprite' => $apiData['sprites']['front_default'] ?? null
        ];
    }
}