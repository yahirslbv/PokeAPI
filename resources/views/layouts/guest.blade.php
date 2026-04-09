<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#2ec2c3', 
                            dark: '#1ca6a6',    
                        },
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-gray-950 text-gray-200 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-gray-900 p-8 rounded-2xl shadow-xl border border-gray-800">
            
            <div class="flex flex-col items-center mb-10">
                <a href="/">
                    <img src="{{ asset('img/pokedex_logo.png') }}" alt="Logo Pokédex" class="w-48 h-auto drop-shadow-lg">
                </a>
            </div>

            {{ $slot }}
            
        </div>
    </div>
</body>
</html>