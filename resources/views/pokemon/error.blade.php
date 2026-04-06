@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 text-center">
        <h1 class="display-1 fw-bold text-danger">404</h1>
        <h2 class="mb-4">Pokémon no encontrado</h2>
        <p class="fs-5 text-muted mb-4">No se pudo encontrar información para el nombre: <strong>{{ $name }}</strong>.</p>
        <a href="{{ route('pokemon.index') }}" class="btn btn-custom btn-lg shadow-sm">Volver al catálogo</a>
    </div>
</div>
@endsection