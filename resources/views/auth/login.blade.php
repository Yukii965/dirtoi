<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg mb-4"
             style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
            <span class="text-white font-black text-3xl">G</span>
        </div>
        <h2 class="text-2xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
            GasyMarket
        </h2>
        <p class="text-sm mt-1" style="color: rgba(253,246,236,0.5)">
            🇲🇬 La marketplace malgache
        </p>
    </div>

    {{-- Message d'erreur session --}}
    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl text-sm"
            style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171">
            {{ session('error') }}
        </div>
    @endif

    {{-- Formulaire --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Adresse email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus placeholder="nom@exemple.mg"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Mot de passe --}}
        <div>
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Mot de passe
            </label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password" placeholder="••••••••"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Se souvenir + mot de passe oublié --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                       class="rounded" style="accent-color: #F4A429">
                <span class="text-sm" style="color: rgba(253,246,236,0.6)">Se souvenir</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm hover:underline transition" style="color: rgba(244,164,41,0.7)">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        {{-- Bouton connexion --}}
        <button type="submit"
                class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest transition-all active:scale-95"
                style="background: #F4A429; color: #2C1A0E">
            Se connecter
        </button>

        {{-- Lien inscription --}}
        <p class="text-center text-sm" style="color: rgba(253,246,236,0.5)">
            Pas encore de compte ?
            <a href="{{ route('register') }}"
               class="font-bold hover:underline" style="color: #F4A429">
                S'inscrire gratuitement
            </a>
        </p>
    </form>
</x-guest-layout>