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
    <div class="col-md-8">
        <form action="{{ route('pokemon.index') }}" method="GET" class="d-flex flex-wrap gap-2">
            <input type="text" name="search" class="form-control shadow-sm" style="flex: 1; min-width: 200px;" placeholder="Buscar por nombre..." value="{{ request('search') }}">
            <select name="type" class="form-select shadow-sm" style="width: auto;">
                <option value="">Todos los tipos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" {{ request('type') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-custom shadow-sm">Buscar</button>
            @if(request()->has('search') || request()->has('type'))
                <a href="{{ route('pokemon.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    @forelse($pokemons as $pokemon)
    <div class="col">
        <div class="card h-100 shadow border-0 position-relative" style="background-color: {{ $typeColors[strtolower($pokemon['types'][0])] ?? '#111827' }}; border-radius: 15px;">
            
            @auth
                <form action="{{ route('pokemon.favorite') }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                    @csrf
                    <input type="hidden" name="name" value="{{ $pokemon['name'] }}">
                    <button type="submit" class="btn border-0 p-0 shadow-none" style="background: transparent; font-size: 1.5rem; line-height: 1;">
                        {{ $pokemon['is_favorite'] ? '⭐' : '☆' }}
                    </button>
                </form>
            @endauth

            <div class="text-center pt-4 pokedex-image-container">
                <!-- Solo dejamos la imagen principal visible -->
                <img src="{{ $pokemon['image'] }}" 
                     onmouseover="this.src='{{ $pokemon['animated'] }}'" 
                     onmouseout="this.src='{{ $pokemon['image'] }}'" 
                     class="img-fluid pokedex-img-hover main-pokemon-img"
                     data-animated="{{ $pokemon['animated'] }}"
                     alt="{{ $pokemon['name'] }}" 
                     style="image-rendering: pixelated; width: 110px; height: 110px; object-fit: contain;">
            </div>
            
            <div class="card-body text-center mt-2">
                <h5 class="card-title fw-bold text-white text-shadow" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    <small class="text-white-50">#{{ str_pad($pokemon['pokedex_number'], 3, '0', STR_PAD_LEFT) }}</small> <br>
                    {{ $pokemon['name'] }}
                </h5>
                
                <div class="mb-3">
                    @foreach($pokemon['types'] as $type)
                        <span class="badge bg-dark bg-opacity-25 text-white text-uppercase shadow-sm border border-light border-opacity-10" style="font-size: 0.7rem;">
                            {{ $type }}
                        </span>
                    @endforeach
                </div>

                <a href="{{ route('pokemon.show', strtolower($pokemon['name'])) }}" class="btn btn-light btn-sm fw-bold w-75 shadow-sm">Ver</a>
            </div>
        </div>
    </div>
        @empty
        <div class="col-12 w-100 mt-5">
            <div class="text-center py-5 shadow-sm rounded-4" style="background-color: #111827; border: 1px solid #1f2937;">
                <p class="fs-4 text-muted mb-0 italic">No se encontró ningún Pokémon con esos criterios.</p>
            </div>
        </div>
    @endforelse
</div>

<style>
    .pokedex-image-container { position: relative; }
    .pokedex-img-hover { transition: transform 0.3s ease, filter 0.3s ease; }
    .card:hover .pokedex-img-hover { transform: scale(1.1) translateY(-10px); filter: drop-shadow(0 10px 15px rgba(46, 194, 195, 0.6)); }
    form button:hover { transform: scale(1.2); transition: 0.2s; }
</style>

<script>
    // ESTA ES LA MAGIA: Espera a que las imágenes estáticas y la página carguen por completo...
    document.addEventListener("DOMContentLoaded", function() {
        // ...y luego empieza a descargar los GIFs en segundo plano sin saturar al navegador.
        const images = document.querySelectorAll('.main-pokemon-img');
        images.forEach(img => {
            const gifUrl = img.getAttribute('data-animated');
            if(gifUrl) {
                const preloader = new Image();
                preloader.src = gifUrl;
            }
        });
    });
</script>
@endsection