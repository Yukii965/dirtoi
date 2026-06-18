<x-app-layout>
    <div class="py-12">
        <div style="max-width:48rem; margin:0 auto; padding:0 1.5rem; display:flex; flex-direction:column; gap:1.5rem;">
            <h1 class="text-4xl font-black" style="font-family:'Playfair Display',serif;color:#F4A429">
                🔒 Politique de confidentialité
            </h1>
            @foreach([
                ['Collecte des données','Nous collectons uniquement les informations nécessaires : nom, email, numéro de téléphone et adresse. Ces données sont utilisées uniquement pour le fonctionnement de la plateforme.'],
                ['Protection des données','Toutes vos données sont chiffrées et stockées de manière sécurisée. Nous n\'vendons jamais vos informations à des tiers.'],
                ['Paiements sécurisés','Notre système Escrow garantit que votre argent est protégé jusqu\'à confirmation de réception du colis.'],
                ['Vos droits','Vous pouvez demander la modification ou suppression de vos données à tout moment en contactant support@gasymarket.mg'],
                ['Règles de publication','❌ Contenu adulte/nudisme interdit | ❌ Armes et drogues interdites | ❌ Produits contrefaits interdits | ❌ Fausses descriptions interdites | ✅ Produits légaux uniquement'],
                ['Sanctions','Tout compte ne respectant pas ces règles sera immédiatement suspendu puis supprimé sans remboursement des frais d\'abonnement.'],
            ] as [$titre, $contenu])
                <div class="p-6 rounded-2xl" style="background:rgba(44,26,14,0.85);border:1px solid rgba(244,164,41,0.2)">
                    <h3 class="font-bold text-lg mb-2" style="color:#F4A429">{{ $titre }}</h3>
                    <p style="color:rgba(253,246,236,0.7);font-size:0.9rem;line-height:1.7">{{ $contenu }}</p>
                </div>
            @endforeach
            <div class="text-center">
                <a href="mailto:support@gasymarket.mg" style="color:#F4A429">📧 support@gasymarket.mg</a>
            </div>
        </div>
    </div>
</x-app-layout>