<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="flex flex-col items-center mb-10">
        <div class="relative w-28 h-28 mb-4">
            <div class="absolute inset-0 bg-blue-600/30 blur-2xl rounded-full"></div>
            <svg viewBox="0 0 100 100" class="relative text-blue-500 drop-shadow-[0_0_20px_rgba(59,130,246,0.6)]">
                <path d="M25,20 C25,10 75,10 75,20" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round"/>
                <path d="M15,30 L85,30 L75,80 L25,80 Z" fill="currentColor" fill-opacity="0.1" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/>
                <line x1="35" y1="30" x2="40" y2="80" stroke="currentColor" stroke-width="3"/>
                <line x1="65" y1="30" x2="60" y2="80" stroke="currentColor" stroke-width="3"/>
                <circle cx="50" cy="55" r="8" fill="currentColor" class="animate-pulse"/>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-white uppercase tracking-[0.2em]">
            <span class="text-blue-500 text-shadow-blue">DirToi</span>
        </h2>
        <div class="h-1 w-20 bg-blue-600 mt-2 rounded-full shadow-[0_0_10px_#2563eb]"></div>
    </div>

    <style>
        .text-shadow-blue {
            text-shadow: 0 0 15px rgba(37, 99, 235, 0.6);
        }
    </style>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Identifiant Réseau</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                   class="w-full bg-gray-950/50 border-gray-800 rounded-xl focus:border-cyan-500 focus:ring-cyan-500 text-white placeholder-gray-600 shadow-inner"
                   placeholder="nom@exemple.mg">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Code d'accès</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-gray-950/50 border-gray-800 rounded-xl focus:border-cyan-500 focus:ring-cyan-500 text-white shadow-inner"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-gray-900 border-gray-800 text-cyan-500 focus:ring-cyan-500" name="remember">
                <span class="ml-2 text-sm text-gray-400 font-medium">Maintenir la liaison</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-500 hover:text-cyan-400 transition-colors" href="{{ route('password.request') }}">
                    Code oublié ?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button class="w-full bg-cyan-500 hover:bg-cyan-400 text-gray-950 font-black py-4 rounded-xl transition-all shadow-[0_0_20px_rgba(6,182,212,0.3)] transform active:scale-95 uppercase tracking-widest">
                Initialiser Connexion
            </button>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            Pas encore de terminal ? 
            <a href="{{ route('register') }}" class="text-cyan-500 hover:underline">Créer un compte</a>
        </p>
    </form>
</x-guest-layout>