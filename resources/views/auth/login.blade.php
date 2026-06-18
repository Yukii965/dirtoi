<x-guest-layout>

    <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg mb-3"
             style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
            <span class="text-white font-black text-2xl">G</span>
        </div>
        <h2 class="text-xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
            Connexion
        </h2>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('error'))
        <div class="mb-4 p-3 rounded-xl text-sm"
             style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="nom@exemple.mg"
                   class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Mot de passe</label>
            <input type="password" name="password" required placeholder="••••••••"
                   class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" style="accent-color: #F4A429">
                <span class="text-xs" style="color: rgba(253,246,236,0.6)">Se souvenir</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs hover:underline"
                   style="color: rgba(244,164,41,0.7)">Mot de passe oublié ?</a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest transition-all active:scale-95"
                style="background: #F4A429; color: #2C1A0E">
            Se connecter
        </button>

        <a href="{{ route('register') }}"
           class="block w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest text-center transition-all"
           style="border: 2px solid #F4A429; color: #F4A429"
           onmouseover="this.style.background='#F4A429'; this.style.color='#2C1A0E'"
           onmouseout="this.style.background='transparent'; this.style.color='#F4A429'">
            Commencer gratuitement
        </a>

    </form>

</x-guest-layout>