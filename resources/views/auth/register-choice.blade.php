<x-guest-layout>
    <div class="text-center mb-8">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg mb-4 mx-auto"
             style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
            <span class="text-white font-black text-3xl">G</span>
        </div>
        <h2 class="text-2xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
            Rejoindre GasyMarket
        </h2>
        <p class="text-sm mt-1" style="color: rgba(253,246,236,0.5)">
            Choisissez votre type de compte
        </p>
    </div>

    <div class="space-y-3">

        {{-- Acheteur --}}
        <a href="{{ route('register.form', 'acheteur') }}"
           class="flex items-center gap-4 p-5 rounded-2xl transition group"
           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(244,164,41,0.2)"
           onmouseover="this.style.borderColor='#F4A429'; this.style.background='rgba(244,164,41,0.1)'"
           onmouseout="this.style.borderColor='rgba(244,164,41,0.2)'; this.style.background='rgba(255,255,255,0.05)'">
            <div class="text-4xl">🛍️</div>
            <div class="text-left">
                <p class="font-bold" style="color: #FDF6EC">Acheteur</p>
                <p class="text-xs mt-0.5" style="color: rgba(253,246,236,0.5)">
                    Je veux acheter des produits sur GasyMarket
                </p>
            </div>
            <span class="ml-auto" style="color: #F4A429">→</span>
        </a>

        {{-- Vendeur Occasionnel --}}
        <a href="{{ route('register.form', 'vendeur_amateur') }}"
           class="flex items-center gap-4 p-5 rounded-2xl transition"
           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(244,164,41,0.2)"
           onmouseover="this.style.borderColor='#F4A429'; this.style.background='rgba(244,164,41,0.1)'"
           onmouseout="this.style.borderColor='rgba(244,164,41,0.2)'; this.style.background='rgba(255,255,255,0.05)'">
            <div class="text-4xl">👤</div>
            <div class="text-left">
                <p class="font-bold" style="color: #FDF6EC">Particulier</p>
                <p class="text-xs mt-0.5" style="color: rgba(253,246,236,0.5)">
                    Vendeur occasionnel ou régulier — 5% de commission par vente
                </p>
            </div>
            <span class="ml-auto" style="color: #F4A429">→</span>
        </a>

        {{-- Entreprise --}}
        <a href="{{ route('register.form', 'vendeur_pro') }}"
           class="flex items-center gap-4 p-5 rounded-2xl transition"
           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(244,164,41,0.2)"
           onmouseover="this.style.borderColor='#F4A429'; this.style.background='rgba(244,164,41,0.1)'"
           onmouseout="this.style.borderColor='rgba(244,164,41,0.2)'; this.style.background='rgba(255,255,255,0.05)'">
            <div class="text-4xl">🏢</div>
            <div class="text-left">
                <p class="font-bold" style="color: #FDF6EC">Entreprise</p>
                <p class="text-xs mt-0.5" style="color: rgba(253,246,236,0.5)">
                    Boutique, commerce, société — abonnement mensuel
                </p>
            </div>
            <span class="ml-auto" style="color: #F4A429">→</span>
        </a>

    </div>

    <p class="text-center text-sm mt-6" style="color: rgba(253,246,236,0.5)">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="font-bold hover:underline" style="color: #F4A429">
            Se connecter
        </a>
    </p>
</x-guest-layout>