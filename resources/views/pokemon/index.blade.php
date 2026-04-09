@extends('layouts.app')

@section('content')
@php
$typeColors = [
    'normal' => '#A8A77A', 'fire' => '#EE8130', 'water' => '#6390F0',
    'electric' => '#F7D02C', 'grass' => '#7AC74C', 'ice' => '#96D9D6',
    'fighting' => '#C22E28', 'poison' => '#A33EA1', 'ground' => '#E2BF65',
    'flying' => '#A890F0', 'psychic' => '#F95587', 'bug' => '#A6B91A',
    'rock' => '#B6A136', 'ghost' => '#735797', 'dragon' => '#6F35FC',
    'dark' => '#705898', 'steel' => '#B7B7CE', 'fairy' => '#D685AD',
];
@endphp

<div class="row mb-4">
<div class="col"><h1 class="text-center fw-bold text-white">Catálogo Pokémon</h1></div>
</div>

<div class="row mb-5 justify-content-center">
    <div class="col-md-6">
        <form action="{{ route('pokemon.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2 shadow-sm" placeholder="Buscar Pokémon por nombre" value="{{ request('search') }}">
            <button type="submit" class="btn btn-custom shadow-sm">Buscar</button>
            @if(request()->has('search'))
                <a href="{{ route('pokemon.index') }}" class="btn btn-outline-secondary ms-2">Limpiar</a>
            @endif
        </form>
        @if(isset($error))
            <div class="text-danger mt-2 small fw-bold text-center">{{ $error }}</div>
        @endif
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    @forelse($pokemons as $pokemon)
    <div class="col">
        <div class="card h-100 shadow border-0" style="background-color: {{ $typeColors[strtolower($pokemon['type'])] ?? '#f8f9fa' }}; border-radius: 15px;">
            <div class="text-center pt-3">
                <img src="{{ $pokemon['image'] }}" 
                     onmouseover="this.src='{{ $pokemon['animated'] }}'" 
                     onmouseout="this.src='{{ $pokemon['image'] }}'" 
                     class="img-fluid bg-white rounded-circle shadow" 
                     alt="{{ $pokemon['name'] }}" 
                     style="image-rendering: pixelated; width: 120px; height: 120px; object-fit: contain; border: 4px solid rgba(255,255,255,0.5); transition: transform 0.2s;">
            </div>
            
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-white text-shadow" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    {{ $pokemon['name'] }}
                </h5>
                <span class="badge bg-light text-dark mb-3 text-uppercase">{{ $pokemon['type'] }}</span>
                <br>
                <a href="{{ route('pokemon.show', strtolower($pokemon['name'])) }}" class="btn btn-light btn-sm fw-bold w-75 shadow-sm">Ver</a>
            </div>
        </div>
    </div>
    @empty
        <div class="col-12 d-flex justify-content-center w-100 mt-5">
            <p class="text-muted fs-5 text-center">No se encontró ningún Pokémon con ese nombre.</p>
        </div>
    @endforelse
</div>
@endsection