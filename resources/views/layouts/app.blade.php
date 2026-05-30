<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GasyMarket — {{ config('app.name') }}</title>

    {{-- Police Google Fonts — style malgache chaleureux --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Variables de couleurs GasyMarket */
        :root {
            --baobab-dark:   #2C1A0E;
            --baobab-brown:  #5C3317;
            --baobab-light:  #8B5E3C;
            --sunset-yellow: #F4A429;
            --sunset-orange: #E07B2A;
            --cream:         #FDF6EC;
            --cream-dark:    #F5E6D0;
        }

        body {
            background-color: var(--baobab-dark);
            color: var(--cream);
            font-family: 'Inter', sans-serif;
        }

        /* Background baobab sur toutes les pages */
        .gasy-bg {
            background:
                linear-gradient(
                    to bottom,
                    rgba(44, 26, 14, 0.85) 0%,
                    rgba(44, 26, 14, 0.75) 50%,
                    rgba(44, 26, 14, 0.90) 100%
                ),
                url('/images/baobab-sunset.jpg') center/cover fixed;
            min-height: 100vh;
        }

        /* Titre style GasyMarket */
        .gasy-title {
            font-family: 'Playfair Display', serif;
            color: var(--sunset-yellow);
        }

        /* Bouton principal */
        .btn-gasy {
            background: var(--sunset-yellow);
            color: var(--baobab-dark);
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s;
        }
        .btn-gasy:hover {
            background: var(--sunset-orange);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(244, 164, 41, 0.3);
        }

        /* Carte produit */
        .gasy-card {
            background: rgba(92, 51, 23, 0.4);
            border: 1px solid rgba(244, 164, 41, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            transition: all 0.2s;
        }
        .gasy-card:hover {
            border-color: rgba(244, 164, 41, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        /* Navbar */
        .gasy-nav {
            background: rgba(44, 26, 14, 0.95);
            border-bottom: 1px solid rgba(244, 164, 41, 0.2);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="gasy-bg">

    {{-- Navigation --}}
    @include('layouts.navigation')

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="fixed top-20 right-4 z-50 bg-green-500/90 text-white px-6 py-3 rounded-xl shadow-lg backdrop-blur"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Message d'erreur --}}
    @if(session('error'))
        <div class="fixed top-20 right-4 z-50 bg-red-500/90 text-white px-6 py-3 rounded-xl shadow-lg backdrop-blur"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Contenu principal --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-16 border-t border-yellow-900/30 py-8 text-center">
        <p class="text-yellow-600/60 text-sm">
            &copy; 2026 GasyMarket — La marketplace malgache 🇲🇬
        </p>
        <div class="mt-2 flex justify-center gap-4 text-xs text-yellow-700/50">
            <a href="#" class="hover:text-yellow-500 transition">Conditions</a>
            <a href="{{ route('nav.help') }}" class="hover:text-yellow-500 transition">Aide</a>
        </div>
    </footer>

    {{-- Chatbot IA --}}
    @include('components.ia-zero')

</body>
</html>