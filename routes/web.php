<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

// Raíz: Ahora configurada para mostrar 'home' por defecto al abrir el proyecto
Route::get('/', function () {
    return view('home');
})->name('home');

/* * Nota: Si se prefiere mantener la vista de bienvenida nativa de Laravel en la raíz 
 * y acceder a 'home' escribiendo "/home" en la URL, se debe usar este código en su lugar:
 *
 * Route::get('/', function () {
 * return view('welcome');
 * });
 *
 * Route::get('/home', function () {
 * return view('home');
 * })->name('home');
 */

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    
    // Rutas del catálogo de Pokémon
    Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.index');
    Route::get('/pokemon/{name}', [PokemonController::class, 'show'])->name('pokemon.show');
    
    // Ruta "Acerca de" restaurada
    Route::get('/about', function () {
        return view('about');
    })->name('about');
    
});

// Ruta del Dashboard configurada correctamente (sin duplicados)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil que instala Breeze
Route::middleware('auth')->group(function () {
    Route::get('/favoritos', [PokemonController::class, 'favorites'])->name('pokemon.favorites');
    Route::post('/pokemon/favorite', [PokemonController::class, 'toggleFavorite'])->name('pokemon.favorite');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';