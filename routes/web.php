<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

// Si alguien entra a la raíz, ve la pantalla de bienvenida nativa de Laravel
Route::get('/', function () {
    return view('welcome');
});

// Agrupamos nuestras rutas del proyecto bajo el middleware 'auth'
Route::middleware('auth')->group(function () {
    // Si intentan entrar aquí sin iniciar sesión, Laravel los patea al /login automáticamente
    Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.index');
    Route::get('/pokemon/{name}', [PokemonController::class, 'show'])->name('pokemon.show');
    
    Route::get('/about', function () {
        return view('about');
    })->name('about');
});

// Breeze crea una ruta 'dashboard' por defecto. La redirigimos a tu catálogo para mantener el flujo.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil que instala Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';