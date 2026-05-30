<x-guest-layout>
    <div class="text-center">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg mb-4"
                 style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
                <span class="text-white font-black text-3xl">G</span>
            </div>
            <h2 class="text-2xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                GasyMarket
            </h2>
        </div>

        {{-- Icône attente --}}
        <div class="text-6xl mb-6">⏳</div>

        {{-- Titre --}}
        <h1 class="text-2xl font-black mb-4"
            style="font-family: 'Playfair Display', serif; color: #FDF6EC">
            Compte en attente de validation
        </h1>

        {{-- Message --}}
        <div class="p-6 rounded-2xl mb-6 text-left space-y-3"
             style="background: rgba(244,164,41,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <p style="color: rgba(253,246,236,0.8)">
                👋 Bonjour <strong style="color: #F4A429">{{ session('pending_name') }}</strong> !
            </p>
            <p style="color: rgba(253,246,236,0.7)">
                Votre compte a bien été créé. Notre équipe va vérifier vos informations avant de vous donner accès à la plateforme.
            </p>
            <p style="color: rgba(253,246,236,0.7)">
                ✅ Vous recevrez une confirmation sous les <strong style="color: #F4A429">24 heures</strong>.
            </p>
            <p style="color: rgba(253,246,236,0.7)">
                📧 Une notification vous sera envoyée à <strong style="color: #F4A429">{{ session('pending_email') }}</strong>.
            </p>
        </div>

        {{-- Info contact --}}
        <p class="text-sm mb-6" style="color: rgba(253,246,236,0.4)">
            Des questions ? Contactez-nous à
            <a href="mailto:support@gasymarket.mg"
               class="hover:underline" style="color: #F4A429">
                support@gasymarket.mg
            </a>
        </p>

        {{-- Bouton retour --}}
        <a href="{{ route('login') }}"
           class="block w-full py-3 rounded-xl font-bold text-sm text-center transition"
           style="background: rgba(244,164,41,0.1); border: 1px solid rgba(244,164,41,0.2); color: #F4A429">
            ← Retour à la connexion
        </a>

    </div>
</x-guest-layout>