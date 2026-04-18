<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>DirToi - Authentification</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-950 text-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[url('https://source.unsplash.com/featured/?cyberpunk,grid')] bg-cover bg-center">
            <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm"></div>

            <div class="relative z-10 text-center mb-8">
                <a href="/" class="text-5xl font-black tracking-tighter text-white">
                    DIR<span class="text-cyan-500">TOI</span>
                </a>
                <p class="text-cyan-500/50 font-mono text-xs mt-2 uppercase tracking-[0.3em]">Secure Access Terminal</p>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-gray-900/40 backdrop-blur-xl border border-white/10 shadow-[0_0_50px_rgba(0,0,0,0.5)] sm:rounded-[2rem]">
                <div class="absolute -top-px left-1/2 -translate-x-1/2 w-3/4 h-px bg-gradient-to-r from-transparent via-cyan-500 to-transparent"></div>
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>