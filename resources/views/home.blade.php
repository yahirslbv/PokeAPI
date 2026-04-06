@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 70vh;">
    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png" alt="Pokeball" style="width: 150px;" class="mb-3">
    <h1 class="display-3 fw-bold text-danger mb-3">Pokédex Web</h1>
    <p class="lead mb-5 text-muted">Explora el catálogo de Pokémon.</p>
    
    <a href="{{ route('pokemon.index') }}" class="btn btn-danger btn-lg px-5 py-3 rounded-pill shadow fw-bold">
        Entrar a la Pokédex
    </a>
</div>
@endsection