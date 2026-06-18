<x-app-layout>
    <div class="py-12">
        <div style="max-width:48rem; margin:0 auto; padding:0 1.5rem; display:flex; flex-direction:column; gap:1.5rem;">
            <h1 class="text-4xl font-black" style="font-family:'Playfair Display',serif;color:#F4A429">
                📋 Conditions d'utilisation
            </h1>
            @foreach([
                ['Acceptation','En utilisant GasyMarket, vous acceptez ces conditions. Si vous n\'êtes pas d\'accord, veuillez ne pas utiliser notre plateforme.'],
                ['Comptes utilisateurs','Vous êtes responsable de la sécurité de votre compte. Tout abus signalé entraîne une suspension immédiate.'],
                ['Commissions','Les vendeurs particuliers paient 5% de commission par vente. Les entreprises paient un abonnement mensuel fixe.'],
                ['Système Escrow','L\'argent est retenu par GasyMarket jusqu\'à confirmation de livraison par l\'acheteur. Aucun paiement direct entre acheteur et vendeur n\'est autorisé hors plateforme.'],
                ['Litiges','En cas de litige, GasyMarket enquête et décide du remboursement ou du déblocage des fonds sous 72h.'],
                ['Responsabilité','GasyMarket n\'est pas responsable des produits vendus par les vendeurs. Chaque vendeur est responsable de la qualité et conformité de ses produits.'],
            ] as [$titre, $contenu])
                <div class="p-6 rounded-2xl" style="background:rgba(44,26,14,0.85);border:1px solid rgba(244,164,41,0.2)">
                    <h3 class="font-bold text-lg mb-2" style="color:#F4A429">{{ $titre }}</h3>
                    <p style="color:rgba(253,246,236,0.7);font-size:0.9rem;line-height:1.7">{{ $contenu }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>