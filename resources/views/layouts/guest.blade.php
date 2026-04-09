<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PokeMMO Style</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#2ec2c3', // Tu color principal cyan
                            dark: '#1ca6a6',    // Un tono más oscuro para el hover
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
                <div class="flex flex-col items-center mb-10">
                    <img src="{{ asset('img/pokedex_logo.png') }}" alt="PokeMMO Logo" class="w-48 h-auto drop-shadow-lg">
                </div>
            </div>

            <form>
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor">
                                <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                            </svg>
                        </div>
                        <input type="text" id="username" name="username" placeholder="Tu nombre de usuario" class="w-full bg-gray-800 border border-gray-700 rounded-lg text-white p-3 pl-12 focus:border-brand focus:ring-brand focus:ring-2 outline-none transition duration-150">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor">
                                <path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80zM320 240H128c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h192c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Tu contraseña" class="w-full bg-gray-800 border border-gray-700 rounded-lg text-white p-3 pl-12 focus:border-brand focus:ring-brand focus:ring-2 outline-none transition duration-150">
                    </div>
                </div>

                <div class="flex justify-end mb-6">
                    <a href="#" class="text-sm text-gray-400 hover:text-brand transition duration-150">Forgot password?</a>
                </div>

                <div class="mb-6">
                    <button type="submit" class="w-full bg-brand text-black font-bold p-3 rounded-lg hover:bg-brand-dark transition duration-150">Login</button>
                </div>

                <div class="text-center text-sm text-gray-400">
                    <span>Don't have an account?</span>
                    <a href="#" class="text-brand font-semibold hover:text-brand-dark transition duration-150 ml-1">Create one</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>