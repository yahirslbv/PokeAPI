@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold" style="color: #2ec2c3;">Dashboard</h2>
        <hr style="border-color: #1f2937;">
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="background-color: #111827;">
            <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between p-4">
                <div>
                    <h3 class="fw-bold mb-2" style="color: #2ec2c3;">¡Bienvenido al Panel de Entrenador!</h3>
                    <p class="text-muted mb-0 fs-5">Has accedido correctamente a tu central de mando, {{ Auth::user()->name }}.</p>
                </div>
                <div class="d-none d-md-block mt-3 mt-md-0">
                    <img src="{{ asset('img/pokedex_logo.png') }}" alt="Logo" style="height: 80px; opacity: 0.9;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <a href="{{ route('pokemon.index') }}" class="text-decoration-none">
            <div class="card h-100 shadow border-0 transition-hover" style="background-color: #111827; border: 1px solid #1f2937 !important;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 rounded-3" style="background-color: #2ec2c3; color: #000;">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h4 class="fw-bold ms-3 mb-0" style="color: #2ec2c3;">Catálogo Pokémon</h4>
                    </div>
                    <p class="text-muted mb-0">Accede a la base de datos completa de Pokémon, revisa sus estadísticas y gestiona la información.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6">
        <a href="{{ route('profile.edit') }}" class="text-decoration-none">
            <div class="card h-100 shadow border-0 transition-hover" style="background-color: #111827; border: 1px solid #1f2937 !important;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 rounded-3" style="background-color: #2ec2c3; color: #000;">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="fw-bold ms-3 mb-0" style="color: #2ec2c3;">Mi Perfil</h4>
                    </div>
                    <p class="text-muted mb-0">Gestiona tu cuenta de usuario, actualiza tus datos personales y cambia tu contraseña de seguridad.</p>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s ease;
    }
    .transition-hover:hover {
        border-color: #2ec2c3 !important;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(46, 194, 195, 0.1) !important;
    }
</style>
@endsection