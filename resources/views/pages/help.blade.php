<x-app-layout>
    <div class="py-12">
        <div style="max-width:48rem; margin:0 auto; padding:0 1.5rem;">

            {{-- Titre --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                    Centre d'assistance
                </h1>
                <p class="mt-2 text-sm" style="color: rgba(253,246,236,0.5)">
                    GasyMarket — On est là pour vous aider 🇲🇬
                </p>
            </div>

            <div style="display:flex; flex-direction:column; gap:1.5rem;">

                {{-- FAQ --}}
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="font-bold text-lg mb-2" style="color: #F4A429">
                        Comment suivre ma commande ?
                    </h3>
                    <p style="color: rgba(253,246,236,0.7)">
                        Connectez-vous à votre tableau de bord acheteur — vous verrez le statut en temps réel de chaque commande.
                    </p>
                </div>

                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="font-bold text-lg mb-2" style="color: #F4A429">
                        Quels sont les délais de livraison ?
                    </h3>
                    <p style="color: rgba(253,246,236,0.7)">
                        Pour Antananarivo, la livraison est généralement sous 24h. Pour les autres provinces, comptez 2 à 5 jours selon le vendeur.
                    </p>
                </div>

                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="font-bold text-lg mb-2" style="color: #F4A429">
                        Comment fonctionne le paiement sécurisé ?
                    </h3>
                    <p style="color: rgba(253,246,236,0.7)">
                        Votre argent est retenu chez GasyMarket jusqu'à ce que vous confirmiez la réception du colis. Le vendeur ne reçoit son paiement qu'après votre confirmation — vous êtes toujours protégé.
                    </p>
                </div>

                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="font-bold text-lg mb-2" style="color: #F4A429">
                        Comment devenir vendeur ?
                    </h3>
                    <p style="color: rgba(253,246,236,0.7)">
                        Créez un compte en choisissant "Particulier" ou "Entreprise" lors de l'inscription. Vous pourrez ensuite ajouter vos produits depuis votre tableau de bord vendeur.
                    </p>
                </div>

                {{-- Contact --}}
                <div class="p-10 rounded-2xl text-center mt-8"
                     style="background: linear-gradient(135deg, rgba(92,51,23,0.6), rgba(44,26,14,0.8)); border: 1px solid rgba(244,164,41,0.3)">
                    <h2 class="text-2xl font-black mb-3" style="color: #FDF6EC; font-family: 'Playfair Display', serif">
                        Besoin d'aide personnalisée ?
                    </h2>
                    <p class="mb-6" style="color: rgba(253,246,236,0.6)">
                        Notre équipe est disponible 7j/7 pour vous répondre.
                    </p>
                    <a href="mailto:support@gasymarket.mg"
                       class="text-xl font-bold hover:underline" style="color: #F4A429">
                        support@gasymarket.mg
                    </a>
                </div>

            </div>
        </div>
    </div>
    <style>
        @media (max-width: 640px) {
            .max-w-3xl { padding: 0 0.75rem !important; }
            .p-6 { padding: 1rem !important; }
        }
    </style>
</x-app-layout>