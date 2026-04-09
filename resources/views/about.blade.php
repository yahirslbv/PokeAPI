@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8 text-center">
        <h1 class="display-5 fw-bold mb-4" style="color: #2ec2c3;">Acerca de</h1>
        
        <div class="card shadow-sm border-0 p-4" style="background-color: #212529;">
            <div class="card-body">
                <h3 class="fw-bold mb-3" style="color: #2ec2c3;">Equipo</h3>
                <p class="fs-5" style="color: #e6f8f8;">Victor Yahir Medrano Barrera</p>
                
                <hr class="my-4" style="border-color: #2ec2c3; opacity: 0.5;">
                
                <h3 class="fw-bold mb-3" style="color: #2ec2c3;">Objetivo del Proyecto</h3>
                <p class="fs-5" style="color: #e6f8f8;">
                    Integrar librerías mediante Composer para acelerar el desarrollo, implementar el patrón MVC en Laravel y consumir una API real (PokéAPI) para el catálogo web, logrando una interfaz navegable y manejo de errores.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection