@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8 text-center">
        <h1 class="display-5 fw-bold text-danger mb-4">Acerca de</h1>
        
        <div class="card shadow-sm border-0 bg-light p-4">
            <div class="card-body">
                <h3 class="fw-bold mb-3">Equipo</h3>
                <p class="fs-5 text-muted">Victor Yahir Medrano Barrera</p>
                
                <hr class="my-4">
                
                <h3 class="fw-bold mb-3">Objetivo del Proyecto</h3>
                <p class="fs-5 text-muted">
                    Integrar librerías mediante Composer para acelerar el desarrollo, implementar el patrón MVC en Laravel y consumir una API real (PokéAPI) para el catálogo web, logrando una interfaz navegable y manejo de errores.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection