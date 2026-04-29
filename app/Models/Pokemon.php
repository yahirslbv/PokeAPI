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
}