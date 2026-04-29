<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    // Agregamos pokedex_number a los campos permitidos
protected $fillable = [
    'user_id', 'pokedex_number', 'name', 'type', 'image', 'animated', 'hp', 'attack', 'defense'];
    }