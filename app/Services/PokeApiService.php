<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PokeApiService
{
    public function getList($limit = 1500)
    {
        return Http::timeout(5)->withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon?limit={$limit}");
    }

    public function getType($type)
    {
        return Http::timeout(5)->withoutVerifying()->get("https://pokeapi.co/api/v2/type/{$type}");
    }

    public function getPokemon($name)
    {
        return Http::timeout(5)->withoutVerifying()->get("https://pokeapi.co/api/v2/pokemon/" . strtolower($name));
    }

    public function getFromUrl($url)
    {
        return Http::timeout(5)->withoutVerifying()->get($url);
    }
}