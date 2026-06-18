<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

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
                Créez votre compte gratuitement
            </p>
        </div>

        {{-- Nom complet --}}
        <div class="mb-4">
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Nom complet
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                   required autofocus placeholder="Ex: Jean Rakoto"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                   required placeholder="nom@exemple.mg"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Mot de passe --}}
        <div class="mb-4">
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Mot de passe
            </label>
            <input type="password" name="password"
                   required autocomplete="new-password" placeholder="••••••••"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmer mot de passe --}}
        <div class="mb-6">
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Confirmer le mot de passe
            </label>
            <input type="password" name="password_confirmation"
                   required placeholder="••••••••"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Choix du rôle --}}
        <div class="mb-6">
            <label class="block text-xs font-bold uppercase mb-3" style="color: rgba(244,164,41,0.8)">
                Je suis...
            </label>
            <div id="role-grid">

                {{-- Acheteur --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="acheteur" class="hidden"
                           {{ old('role', 'acheteur') === 'acheteur' ? 'checked' : '' }}>
                    <div class="role-option p-3 rounded-xl text-center transition border"
                         style="background: rgba(255,255,255,0.05); border-color: rgba(244,164,41,0.2)">
                        <div class="text-2xl mb-1">🛍️</div>
                        <div class="text-sm font-bold" style="color: #FDF6EC">Acheteur</div>
                        <div class="text-xs mt-1" style="color: rgba(253,246,236,0.5)">J'achète</div>
                    </div>
                </label>

                {{-- Particulier --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="vendeur_amateur" class="hidden"
                           {{ old('role') === 'vendeur_amateur' ? 'checked' : '' }}>
                    <div class="role-option p-3 rounded-xl text-center transition border"
                         style="background: rgba(255,255,255,0.05); border-color: rgba(244,164,41,0.2)">
                        <div class="text-2xl mb-1">👤</div>
                        <div class="text-sm font-bold" style="color: #FDF6EC">Particulier</div>
                        <div class="text-xs mt-1" style="color: rgba(253,246,236,0.5)">5% par vente</div>
                    </div>
                </label>

                {{-- Entreprise --}}
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="vendeur_pro" class="hidden"
                           {{ old('role') === 'vendeur_pro' ? 'checked' : '' }}>
                    <div class="role-option p-3 rounded-xl text-center transition border"
                         style="background: rgba(255,255,255,0.05); border-color: rgba(244,164,41,0.2)">
                        <div class="text-2xl mb-1">🏢</div>
                        <div class="text-sm font-bold" style="color: #FDF6EC">Entreprise</div>
                        <div class="text-xs mt-1" style="color: rgba(253,246,236,0.5)">Abonnement</div>
                    </div>
                </label>

            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Numéro MVola (vendeurs) --}}
        <div class="mb-4 vendeur-field hidden">
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Numéro MVola
            </label>
            <input type="text" name="mvola_number" value="{{ old('mvola_number') }}"
                   placeholder="034 XX XXX XX"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <p class="text-xs mt-1" style="color: rgba(253,246,236,0.4)">Pour recevoir vos paiements</p>
            <x-input-error :messages="$errors->get('mvola_number')" class="mt-2" />
        </div>

        {{-- Nom entreprise --}}
        <div class="mb-6 entreprise-field hidden">
            <label class="block text-xs font-bold uppercase mb-2" style="color: rgba(244,164,41,0.8)">
                Nom de l'entreprise
            </label>
            <input type="text" name="company_name" value="{{ old('company_name') }}"
                   placeholder="Ex: Société XYZ Madagascar"
                   class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        {{-- Bouton s'inscrire --}}
        <button type="submit"
                class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest transition-all active:scale-95"
                style="background: #F4A429; color: #2C1A0E">
            S'inscrire
        </button>

        {{-- Lien connexion --}}
        <p class="text-center text-sm mt-4" style="color: rgba(253,246,236,0.5)">
            Déjà inscrit ?
            <a href="{{ route('login') }}"
               class="font-bold hover:underline" style="color: #F4A429">
                Se connecter
            </a>
        </p>
    </form>

    {{-- Script rôles --}}
    <script>
        const roleInputs = document.querySelectorAll('input[name="role"]');
        const vendeurFields = document.querySelectorAll('.vendeur-field');
        const entrepriseFields = document.querySelectorAll('.entreprise-field');
        const roleOptions = document.querySelectorAll('.role-option');

        function updateFields() {
            const selected = document.querySelector('input[name="role"]:checked')?.value;

            // Afficher/cacher les champs
            vendeurFields.forEach(f => f.classList.toggle('hidden', selected === 'acheteur'));
            entrepriseFields.forEach(f => f.classList.toggle('hidden', selected !== 'vendeur_pro'));

            // Mettre à jour le style des cartes
            roleInputs.forEach((input, i) => {
                const colors = { acheteur: '#F4A429', vendeur_amateur: '#F4A429', vendeur_pro: '#F4A429' };
                if (input.checked) {
                    roleOptions[i].style.borderColor = colors[input.value];
                    roleOptions[i].style.background = 'rgba(244,164,41,0.15)';
                } else {
                    roleOptions[i].style.borderColor = 'rgba(244,164,41,0.2)';
                    roleOptions[i].style.background = 'rgba(255,255,255,0.05)';
                }
            });
        }

        roleInputs.forEach(input => input.addEventListener('change', updateFields));
        updateFields();
    </script>
    <style>
        #role-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }
        @media (max-width: 480px) {
            #role-grid { grid-template-columns: 1fr; }
        }
    </style>
</x-guest-layout>