<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
<style>
        /* Fondo general y tipografía */
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: #192436; /* Azul oscuro grisáceo */
            color: #e2e8f0; 
        }
        
        /* Barra de navegación */
        .navbar-custom { 
            background-color: #0f172a; 
            border-bottom: 2px solid #3b82f6; /* Línea de acento azul brillante */
        }
        .navbar-brand, .nav-link {
            color: #e2e8f0 !important;
        }

        /* Tarjetas (Cards) */
        .card {
            background-color: #1e293b !important; /* Fondo de tarjetas oscuro */
            border: 1px solid #334155 !important;
            border-radius: 10px;
        }
        .card-body.bg-light {
            background-color: #1e293b !important; /* Sobrescribe la clase bg-light de Bootstrap */
            color: #f8fafc !important;
        }
        
        /* Textos y títulos dentro de las tarjetas */
        .card-title, .badge.bg-light {
            color: #f8fafc !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .text-muted {
            color: #94a3b8 !important; /* Gris claro para legibilidad */
        }

        /* Botones personalizados */
        .btn-custom { 
            background-color: #3b82f6; 
            color: #ffffff; 
            font-weight: bold; 
            border: none; 
        }
        .btn-custom:hover { 
            background-color: #2563eb; 
            color: #ffffff; 
        }
        .btn-light, .btn-outline-danger {
            background-color: transparent !important;
            border: 1px solid #3b82f6 !important;
            color: #3b82f6 !important;
        }
        .btn-light:hover, .btn-outline-danger:hover {
            background-color: #3b82f6 !important;
            color: #ffffff !important;
        }

        /* Buscador (Inputs) */
        .form-control {
            background-color: #0f172a;
            color: #ffffff;
            border: 1px solid #334155;
        }
        .form-control:focus {
            background-color: #1e293b;
            color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        .form-control::placeholder {
            color: #64748b;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Pokédex Web</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('pokemon.index') }}">Pokémon</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Acerca de</a></li>
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