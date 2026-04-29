@extends('layouts.app')

@php
$typeColors = [
    'normal' => '#A8A77A', 'fire' => '#EE8130', 'water' => '#6390F0',
    'electric' => '#F7D02C', 'grass' => '#7AC74C', 'ice' => '#96D9D6',
    'fighting' => '#C22E28', 'poison' => '#A33EA1', 'ground' => '#E2BF65',
    'flying' => '#A890F0', 'psychic' => '#F95587', 'bug' => '#A6B91A',
    'rock' => '#B6A136', 'ghost' => '#735797', 'dragon' => '#6F35FC',
    'dark' => '#705898', 'steel' => '#B7B7CE', 'fairy' => '#D685AD',
];
$mainTypeColor = $typeColors[strtolower($pokemon['types'][0])] ?? '#f8f9fa';
@endphp

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0" style="background-color: {{ $mainTypeColor }}; border-radius: 20px;">
            <div class="card-header text-center border-0 bg-transparent pt-4 pb-5">
                <h2 class="display-5 fw-bold text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">{{ $pokemon['name'] }}</h2>
            </div>
            
            <div class="card-body text-center" style="border-radius: 0 0 20px 20px; margin-top: 80px; position: relative;">
                
                <div style="position: absolute; top: -90px; left: 50%; transform: translateX(-50%);">
                    <img src="{{ $pokemon['animated'] }}" class="img-fluid bg-white rounded-circle shadow-lg" alt="{{ $pokemon['name'] }}" style="width: 160px; height: 160px; object-fit: contain; border: 5px solid {{ $mainTypeColor }}; padding: 10px;">
                </div>

                <div class="mt-5 pt-4 mb-4">
                    <h5 class="text-muted fw-bold mb-3">Tipos</h5>
                    @foreach($pokemon['types'] as $type)
                        <span class="badge fs-6 me-1" style="background-color: {{ $typeColors[strtolower($type)] ?? '#6c757d' }};">{{ ucfirst($type) }}</span>
                    @endforeach
                </div>

                <hr>

                <div class="row text-center mb-4">
                    <h5 class="text-muted fw-bold mb-3">Estadísticas Base</h5>
                    <div class="col-4">
                        <div class="fw-bold text-danger fs-5">{{ $pokemon['stats']['hp'] ?? 0 }}</div>
                        <small class="text-uppercase text-muted">HP</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-primary fs-5">{{ $pokemon['stats']['attack'] ?? 0 }}</div>
                        <small class="text-uppercase text-muted">Ataque</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-success fs-5">{{ $pokemon['stats']['defense'] ?? 0 }}</div>
                        <small class="text-uppercase text-muted">Defensa</small>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('pokemon.favorite') }}" method="POST">
                            @csrf
                            <input type="hidden" name="name" value="{{ $pokemon['name'] }}">
                            <button type="submit" class="btn {{ $esFavorito ? 'btn-danger' : 'btn-warning' }} fw-bold shadow w-100 rounded-pill fs-5">
                                {{ $esFavorito ? ' Quitar Favorito' : ' Agregar a Favoritos' }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('pokemon.index') }}" class="btn btn-custom w-100 fs-5 rounded-pill shadow-sm">&larr; Volver al catálogo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection