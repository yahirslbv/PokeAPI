<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* Fondo general y tipografía unificados con el tema oscuro */
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: #0a0a0a; /* Gris muy oscuro/negro */
            color: #e2e8f0; 
        }
        
        /* Barra de navegación */
        .navbar-custom { 
            background-color: #111827; /* Tono de los contenedores */
            border-bottom: 2px solid #2ec2c3; /* Línea de acento cyan */
        }
        .navbar-brand, .nav-link {
            color: #e2e8f0 !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #2ec2c3 !important;
        }

        /* Tarjetas (Cards) */
        .card {
            background-color: #111827 !important;
            border: 1px solid #1f2937 !important;
            border-radius: 10px;
        }
        .card-body.bg-light {
            background-color: #111827 !important;
            color: #f8fafc !important;
        }
        
        /* Textos y títulos dentro de las tarjetas */
        .card-title {
            color: #2ec2c3 !important;
        }
        .badge.bg-light {
            color: #2ec2c3 !important;
            background-color: rgba(46, 194, 195, 0.1) !important;
            border: 1px solid rgba(46, 194, 195, 0.3);
        }
        .text-muted {
            color: #9ca3af !important;
        }

        /* Botones personalizados */
        .btn-custom { 
            background-color: #2ec2c3; 
            color: #000000; 
            font-weight: bold; 
            border: none; 
        }
        .btn-custom:hover { 
            background-color: #1ca6a6; 
            color: #000000; 
        }
        .btn-light, .btn-outline-danger {
            background-color: transparent !important;
            border: 1px solid #2ec2c3 !important;
            color: #2ec2c3 !important;
        }
        .btn-light:hover, .btn-outline-danger:hover {
            background-color: #2ec2c3 !important;
            color: #000000 !important;
        }

        /* Buscador (Inputs) */
        .form-control {
            background-color: #111827;
            color: #ffffff;
            border: 1px solid #1f2937;
        }
        .form-control:focus {
            background-color: #1f2937;
            color: #ffffff;
            border-color: #2ec2c3;
            box-shadow: 0 0 0 0.25rem rgba(46, 194, 195, 0.25);
        }
        .form-control::placeholder {
            color: #6b7280;
        }
        
        /* Ajuste para el dropdown (menú desplegable) de Bootstrap */
        .dropdown-menu {
            background-color: #111827;
            border: 1px solid #1f2937;
        }
        .dropdown-item {
            color: #e2e8f0;
        }
        .dropdown-item:hover {
            background-color: #1f2937;
            color: #2ec2c3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <img src="{{ asset('img/pokedex_logo.png') }}" alt="Logo Pokédex" style="height: 40px; width: auto;">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('pokemon.index') }}">Pokémon</a></li>
                        
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-warning" href="{{ route('pokemon.favorites') }}">Mis Favoritos</a>
                        </li>
                        
                        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Acerca de</a></li>
                    @endauth
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-custom ms-2 shadow-sm" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider" style="border-color: #1f2937;"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold" style="background: transparent; border: none; width: 100%; text-align: left;">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>