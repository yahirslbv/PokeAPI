<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $fillable = [
        'user_id', 'pokedex_number', 'name', 'types', 'image', 'animated', 
        'species', 'description', 'height', 'weight', 'hp', 'attack', 
        'defense', 'special_attack', 'special_defense', 'speed', 'evolution_chain'
    ];

    // Esto convierte el texto de la DB en un arreglo de PHP automáticamente
    protected $casts = [
        'types' => 'array',
        'evolution_chain' => 'array',
    ];
    // Método para la misión del Equipo 5
    public function toExportFormat()
    {
        return [
            'name' => $this->name ?? 'Desconocido',
            'types' => is_array($this->types) ? $this->types : (is_string($this->types) && $this->types !== '' ? json_decode($this->types, true) : []),
            'hp' => $this->hp ?? 0,
            'attack' => $this->attack ?? 0,
            'defense' => $this->defense ?? 0,
        ];
    }
}