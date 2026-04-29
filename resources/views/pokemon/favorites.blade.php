@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-warning">Mis Pokémon Favoritos</h1>
        <span class="badge bg-primary fs-5">{{ $pokemons->count() }} Guardados</span>
    </div>

    @if($pokemons->isEmpty())
        <div class="text-center py-5 shadow-sm rounded" style="background-color: rgba(255,255,255,0.05);">
            <p class="fs-4 text-muted">Aún no tienes Pokémon en tu lista local.</p>
            <a href="{{ route('pokemon.index') }}" class="btn btn-custom px-4">Explorar el Catálogo</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($pokemons as $pokemon)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm transition-hover">
                        <div class="text-center p-3" style="background-color: rgba(255,255,255,0.05);">
                            <img src="{{ asset($pokemon->image) }}" 
                                 class="img-fluid" 
                                 style="width: 100px; height: 100px; image-rendering: pixelated;" 
                                 alt="{{ $pokemon->name }}">
                        </div>
                        <div class="card-body text-center">
                            <small class="text-primary fw-bold">#{{ str_pad($pokemon->pokedex_number, 3, '0', STR_PAD_LEFT) }}</small>
                            <h5 class="card-title fw-bold text-white">{{ $pokemon->name }}</h5>
                            <span class="badge bg-secondary text-uppercase mb-3">{{ $pokemon->type }}</span>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('pokemon.show', strtolower($pokemon->name)) }}" class="btn btn-outline-light btn-sm">Ver Detalles</a>
                                
                                <form action="{{ route('pokemon.favorite') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ $pokemon->name }}">
                                    <button type="submit" class="btn btn-link text-danger btn-sm p-0">Eliminar de local</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .transition-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
</style>
@endsection