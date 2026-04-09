<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #2ec2c3;">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen" style="background-color: #0a0a0a; margin-top: -1px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="overflow-hidden shadow-xl sm:rounded-2xl border mb-8" style="background-color: #111827; border-color: #1f2937;">
                <div class="p-8 text-gray-200 flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-3xl font-bold mb-2" style="color: #2ec2c3;">¡Bienvenido al Panel!</h3>
                        <p class="text-gray-400 text-lg">Has iniciado sesión correctamente. ¿Qué te gustaría hacer hoy?</p>
                    </div>
                    <div class="hidden md:block">
                        <img src="{{ asset('img/pokedex_logo.png') }}" alt="Logo Pokédex" class="w-32 h-auto drop-shadow-lg opacity-80">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <a href="{{ route('pokemon.index') }}" class="block p-6 rounded-2xl border shadow-lg transition duration-300" style="background-color: #111827; border-color: #1f2937;" onmouseover="this.style.borderColor='#2ec2c3'" onmouseout="this.style.borderColor='#1f2937'">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full text-black" style="background-color: #2ec2c3;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-semibold ml-4" style="color: #2ec2c3;">Catálogo Pokémon</h4>
                    </div>
                    <p class="text-gray-500">Explora la lista de Pokémon, revisa sus detalles y gestiona la información desde aquí.</p>
                </a>

                <a href="{{ route('profile.edit') }}" class="block p-6 rounded-2xl border shadow-lg transition duration-300" style="background-color: #111827; border-color: #1f2937;" onmouseover="this.style.borderColor='#2ec2c3'" onmouseout="this.style.borderColor='#1f2937'">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full text-black" style="background-color: #2ec2c3;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-semibold ml-4" style="color: #2ec2c3;">Mi Perfil</h4>
                    </div>
                    <p class="text-gray-500">Actualiza tu información personal, cambia tu contraseña o administra tu cuenta.</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>