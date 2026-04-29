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

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #2ec2c3 !important;">
        <h1 class="fw-black text-white mb-0">Mis <span class="text-custom-cyan">Favoritos</span></h1>
        <span class="badge bg-custom-cyan text-dark fs-5 fw-bold rounded-pill shadow-sm">{{ count($pokemons) }}</span>
    </div>

    @if(empty($pokemons))
        <div class="text-center py-5 shadow-sm rounded-4" style="background-color: #111827; border: 1px solid #1f2937;">
            <p class="fs-4 text-muted mb-0 italic">Aún no has guardado ningún Pokémon de forma local.</p>
            <a href="{{ route('pokemon.index') }}" class="btn btn-custom mt-3 px-4">Ir al Catálogo</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($pokemons as $pokemon)
                @php
                    // Convertimos el JSON de SQLite a un arreglo de PHP para poder leer todos los tipos
                    $tiposArray = is_array($pokemon['types']) ? $pokemon['types'] : json_decode($pokemon['types'], true);
                    // Tomamos el primer tipo para pintar el fondo de la tarjeta
                    $tipoPrincipal = $tiposArray[0] ?? 'normal';
                @endphp
                
                <div class="col">
                    <div class="card h-100 shadow border-0 position-relative" style="background-color: {{ $typeColors[strtolower($tipoPrincipal)] ?? '#111827' }}; border-radius: 15px;">
                        
                        <form action="{{ route('pokemon.favorite') }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                            @csrf
                            <input type="hidden" name="name" value="{{ $pokemon['name'] }}">
                            <button type="submit" class="btn border-0 p-0 shadow-none" style="background: transparent; font-size: 1.5rem; line-height: 1;">
                                ⭐
                            </button>
                        </form>

                        <div class="text-center pt-4 pokedex-image-container">
                            <img src="{{ asset($pokemon['image']) }}" 
                                 onmouseover="this.src='{{ asset($pokemon['animated']) }}'" 
                                 onmouseout="this.src='{{ asset($pokemon['image']) }}'" 
                                 class="img-fluid pokedex-img-hover"
                                 alt="{{ $pokemon['name'] }}" 
                                 style="image-rendering: pixelated; width: 110px; height: 110px; object-fit: contain;">
                        </div>
                        
                        <div class="card-body text-center mt-2">
                            <h5 class="card-title fw-bold text-white text-shadow" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                                <small class="text-white-50">#{{ str_pad($pokemon['pokedex_number'], 3, '0', STR_PAD_LEFT) }}</small> <br>
                                {{ $pokemon['name'] }}
                            </h5>
                            
                            <div class="mb-3">
                                @foreach($tiposArray as $tipo)
                                    <span class="badge bg-dark bg-opacity-25 text-white text-uppercase shadow-sm border border-light border-opacity-10" style="font-size: 0.7rem;">
                                        {{ $tipo }}
                                    </span>
                                @endforeach
                            </div>

                            <a href="{{ route('pokemon.show', strtolower($pokemon['name'])) }}" class="btn btn-light btn-sm fw-bold w-75 shadow-sm">Ver</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .pokedex-image-container { position: relative; }
    .pokedex-img-hover { transition: transform 0.3s ease, filter 0.3s ease; }
    .card:hover .pokedex-img-hover { transform: scale(1.1) translateY(-10px); filter: drop-shadow(0 10px 15px rgba(46, 194, 195, 0.6)); }
    .text-custom-cyan { color: #2ec2c3 !important; }
    .bg-custom-cyan { background-color: #2ec2c3 !important; }
    .fw-black { font-weight: 900; }
    .italic { font-style: italic; }
    form button:hover { transform: scale(1.2); transition: 0.2s; }
</style>
@endsection