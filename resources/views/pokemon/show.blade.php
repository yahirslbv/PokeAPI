@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('pokemon.index') }}" class="btn btn-outline-light btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Volver al Catálogo
        </a>
    </div>

    <div class="card shadow-lg border-0 pokedex-entry" style="background-color: #111827; color: #e2e8f0; border-radius: 20px; overflow: hidden;">
        
        <div class="card-header border-0 p-4" style="background: linear-gradient(135deg, rgba(46, 194, 195, 0.2) 0%, rgba(17, 24, 39, 1) 100%);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge bg-custom-cyan text-dark fw-bold mb-2">#{{ str_pad($pokemon['pokedex_number'], 3, '0', STR_PAD_LEFT) }}</span>
                    <h1 class="display-4 fw-black text-white mb-0">{{ $pokemon['name'] }}</h1>
                    <p class="text-info fs-5 italic">{{ $pokemon['species'] }}</p>
                    
                    <div class="mt-2">
                        @foreach($pokemon['types'] as $type)
                            <span class="badge rounded-pill px-3 py-2 text-uppercase me-2" style="background-color: rgba(255,255,255,0.1); border: 1px solid #2ec2c3; color: #2ec2c3; font-size: 0.75rem;">
                                {{ $type }}
                            </span>
                        @endforeach
                    </div>
                </div>
                
                @auth
                    <form action="{{ route('pokemon.favorite') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $pokemon['name'] }}">
                        <button type="submit" 
                                class="btn border-0 shadow-none d-flex align-items-center justify-content-center p-0" 
                                style="background: transparent; width: 60px; height: 60px; font-size: 2.5rem; transition: transform 0.2s ease;">
                            {{ $esFavorito ? '⭐' : '✪' }}
                        </button>
                    </form>
                @endauth
            </div>
            
            <div class="text-center mt-3">
                <img src="{{ $pokemon['animated'] }}" class="img-fluid" style="width: 200px; height: 200px; object-fit: contain; image-rendering: pixelated;" alt="{{ $pokemon['name'] }}">
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h5 class="fw-bold text-custom-cyan mb-3 text-uppercase small tracking-wider">Descripción</h5>
                    <div class="p-4 rounded-4 mb-4" style="background-color: #1f2937; border-left: 5px solid #2ec2c3;">
                        <p class="fs-5 lh-base mb-0 italic" style="color: #d1d5db;">"{{ $pokemon['descripcion'] }}"</p>
                    </div>

                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background-color: rgba(46, 194, 195, 0.05); border: 1px solid rgba(46, 194, 195, 0.1);">
                                <small class="text-muted text-uppercase d-block mb-1">Altura</small>
                                <span class="h4 fw-bold text-white">{{ $pokemon['height'] }} m</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background-color: rgba(46, 194, 195, 0.05); border: 1px solid rgba(46, 194, 195, 0.1);">
                                <small class="text-muted text-uppercase d-block mb-1">Peso</small>
                                <span class="h4 fw-bold text-white">{{ $pokemon['weight'] }} kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h5 class="fw-bold text-custom-cyan mb-4 text-uppercase small tracking-wider">Estadísticas Base</h5>
                    @foreach($pokemon['stats'] as $statName => $statValue)
                        @if($statName !== 'total')
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-uppercase small fw-bold text-muted">{{ str_replace('-', ' ', $statName) }}</span>
                                    <span class="fw-bold text-white">{{ $statValue }}</span>
                                </div>
                                <div class="progress" style="height: 8px; background-color: #374151; border-radius: 10px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($statValue / 255) * 100 }}%; background-color: #2ec2c3; border-radius: 10px;" 
                                         aria-valuenow="{{ $statValue }}" aria-valuemin="0" aria-valuemax="255"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary">
                        <span class="h5 fw-bold text-white mb-0">TOTAL</span>
                        <span class="h4 fw-black text-custom-cyan mb-0">{{ $pokemon['stats']['total'] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h5 class="fw-bold text-custom-cyan mb-4 text-uppercase text-center small tracking-wider">Línea Evolutiva</h5>
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-4 p-4 rounded-4" style="background-color: rgba(255,255,255,0.02);">
                    @foreach($pokemon['evoluciones'] as $index => $evo)
                        <a href="{{ route('pokemon.show', strtolower($evo['name'])) }}" class="text-decoration-none pokedex-evo-link">
                            <div class="text-center pokedex-evo-item {{ $evo['name'] === $pokemon['name'] ? 'pokedex-evo-current' : '' }}">
                                <div class="evo-circle mb-2 shadow">
                                    <img src="{{ $evo['sprite'] }}" alt="{{ $evo['name'] }}" style="width: 70px; image-rendering: pixelated;">
                                </div>
                                <p class="small mb-0 fw-bold {{ $evo['name'] === $pokemon['name'] ? 'text-custom-cyan' : 'text-muted' }}">
                                    {{ $evo['name'] }}
                                </p>
                            </div>
                        </a>
                        
                        @if($index < count($pokemon['evoluciones']) - 1)
                            <div class="text-muted opacity-25">
                                <i class="bi bi-chevron-right fs-4"></i>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Estilos del Botón Favorito */
    .btn:hover {
        transform: scale(1.2);
    }

    /* Colores y Tipografía */
    .text-custom-cyan { color: #2ec2c3 !important; }
    .bg-custom-cyan { background-color: #2ec2c3 !important; }
    .fw-black { font-weight: 900; }
    .tracking-wider { letter-spacing: 0.1em; }
    .italic { font-style: italic; }

    /* Estilos de la Línea Evolutiva */
    .evo-circle {
        width: 90px;
        height: 90px;
        background-color: #1f2937;
        border: 2px solid #374151;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .pokedex-evo-link:hover .evo-circle {
        border-color: #2ec2c3;
        background-color: rgba(46, 194, 195, 0.1);
        transform: scale(1.1);
    }

    .pokedex-evo-current .evo-circle {
        border-color: #2ec2c3;
        background-color: rgba(46, 194, 195, 0.2);
        box-shadow: 0 0 15px rgba(46, 194, 195, 0.4);
    }

    .pokedex-evo-current p {
        text-shadow: 0 0 5px rgba(46, 194, 195, 0.5);
    }

    @media (max-width: 576px) {
        .evo-circle { width: 70px; height: 70px; }
        .evo-circle img { width: 50px !important; }
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection