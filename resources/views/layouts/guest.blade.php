<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GasyMarket — Authentification</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background:
                linear-gradient(
                    to bottom,
                    rgba(44, 26, 14, 0.88) 0%,
                    rgba(44, 26, 14, 0.78) 50%,
                    rgba(44, 26, 14, 0.92) 100%
                ),
                url('/images/baobab-sunset.jpg') center/cover fixed;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen px-4 py-12">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg"
             style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
            <span class="text-white font-black text-2xl">G</span>
        </div>
        <span class="font-black text-3xl" style="font-family: 'Playfair Display', serif;">
            <span style="color: #F4A429">Gasy</span><span class="text-white">Market</span>
        </span>
    </a>

    {{-- Carte formulaire --}}
    <div class="w-full max-w-md rounded-2xl p-8 shadow-2xl"
         style="background: rgba(44, 26, 14, 0.9); border: 1px solid rgba(244, 164, 41, 0.2);">
        {{ $slot }}
    </div>

    {{-- Tagline --}}
    <p class="mt-6 text-sm" style="color: rgba(244, 164, 41, 0.5)">
        🇲🇬 La marketplace 100% malgache
    </p>

</body>
</html>