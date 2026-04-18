<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        </div>
    @endif
    <svg viewBox="0 0 200 200" xmlns='http://www.w3.org/2000/svg' class="fixed inset-0 w-full h-full opacity-[0.03] pointer-events-none z-50">
        <filter id='noiseFilter'>
            <feTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/>
        </filter>
        <rect width='100%' height='100%' filter='url(#noiseFilter)'/>
    </svg>
    <body class="font-sans antialiased bg-gray-950 text-gray-100 overflow-x-hidden">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#0f172a,#020617)]"></div>
            
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-cyan-500/10 blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-yellow-500/10 blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

            <div class="absolute inset-0 opacity-20" 
                style="background-image: linear-gradient(#1e293b 1px, transparent 1px), linear-gradient(90deg, #1e293b 1px, transparent 1px); background-size: 50px 50px; transform: perspective(500px) rotateX(60deg) translateY(-100px); background-repeat: repeat; animation: grid-move 20s linear infinite;">
            </div>
        </div>

        <style>
            @keyframes grid-move {
                0% { background-position: 0 0; }
                100% { background-position: 0 50px; }
            }
        </style>

        <div class="min-h-screen relative z-10">
            @include('layouts.navigation')
            <main class="relative z-10">
                {{ $slot }}
            </main>

            @include('components.ia-zero')
        </div>
    </body>
    <footer class="bg-gray-900 text-gray-400 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2026 DirToi - Développé avec Laravel.</p>
            <div class="mt-4 space-x-4">
                <a href="#" class="hover:text-white">Conditions</a>
                <a href="#" class="hover:text-white">Aide</a>
            </div>
        </div>
    </footer>
</html>
