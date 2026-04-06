<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

// Ruta raíz (/) - Redirige automáticamente al catálogo
Route::get('/', function () {
    return view('home');
});

// Ruta principal del catálogo (/pokemon)
Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.index');

// Ruta para el detalle de cada pokemon (/pokemon/{name})
Route::get('/pokemon/{name}', [PokemonController::class, 'show'])->name('pokemon.show');

// Ruta de "Acerca de"
Route::get('/about', function () {
    return view('about');
})->name('about');