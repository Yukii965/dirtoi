<x-guest-layout>

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('register') }}" style="color: rgba(244,164,41,0.6)">←</a>
        <div>
            <h2 class="text-xl font-black" style="color: #F4A429; font-family: 'Playfair Display', serif">
                @if($role === 'acheteur') 🛍️ Créer un compte Acheteur
                @elseif($role === 'vendeur_amateur') 👤 Compte Particulier
                @else 🏢 Compte Entreprise
                @endif
            </h2>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3)">
            @foreach($errors->all() as $error)
                <p class="text-red-400 text-xs">⚠️ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">

        @if($role === 'acheteur')
            {{-- FORMULAIRE ACHETEUR --}}
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Nom</label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                           placeholder="Rakoto"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Prénom</label>
                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                           placeholder="Jean"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Date de naissance</label>
                    <input type="date" name="birth_date" required value="{{ old('birth_date') }}"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Genre</label>
                    <select name="gender" required
                            class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                            style="background: rgba(44,26,14,0.95); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                        <option value="">Choisir...</option>
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Adresse</label>
                <input type="text" name="address" required value="{{ old('address') }}"
                       placeholder="Ex: Lot 123, Analakely, Antananarivo"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° CIN</label>
                    <input type="text" name="cin_number" required value="{{ old('cin_number') }}"
                           placeholder="123 456 789 012"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° Téléphone</label>
                    <input type="text" name="phone" required value="{{ old('phone') }}"
                           placeholder="034 XX XXX XX"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>

        @elseif($role === 'vendeur_amateur')
            {{-- FORMULAIRE PARTICULIER --}}
            <div class="grid grid-cols-2 gap-3 p-3 rounded-xl mb-2"
                 style="background: rgba(244,164,41,0.08); border: 1px solid rgba(244,164,41,0.15)">
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg"
                       style="border: 1px solid rgba(244,164,41,0.2)">
                    <input type="radio" name="vendor_type" value="occasionnel"
                           class="hidden" id="occasionnel">
                    <div class="vendor-type-opt w-full text-center">
                        <p class="text-sm font-bold" style="color: #FDF6EC">Occasionnel</p>
                        <p class="text-xs" style="color: rgba(253,246,236,0.5)">Je vends de temps en temps</p>
                    </div>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg"
                       style="border: 1px solid rgba(244,164,41,0.2)">
                    <input type="radio" name="vendor_type" value="regulier"
                           class="hidden" id="regulier">
                    <div class="vendor-type-opt w-full text-center">
                        <p class="text-sm font-bold" style="color: #FDF6EC">Régulier</p>
                        <p class="text-xs" style="color: rgba(253,246,236,0.5)">Je vends régulièrement</p>
                    </div>
                </label>
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Nom</label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                           placeholder="Rakoto"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Prénom</label>
                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                           placeholder="Jean"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Date de naissance</label>
                    <input type="date" name="birth_date" required
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Genre</label>
                    <select name="gender" required
                            class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                            style="background: rgba(44,26,14,0.95); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                        <option value="">Choisir...</option>
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Adresse</label>
                <input type="text" name="address" required value="{{ old('address') }}"
                       placeholder="Ex: Lot 123, Analakely"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° Téléphone</label>
                    <input type="text" name="phone" required value="{{ old('phone') }}"
                           placeholder="034 XX XXX XX"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° CIN</label>
                    <input type="text" name="cin_number" required value="{{ old('cin_number') }}"
                           placeholder="123 456 789 012"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Copie CIN (PDF)
                </label>
                <input type="file" name="cin_pdf" accept=".pdf" required
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: rgba(253,246,236,0.7)">
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Photo d'identité
                </label>
                <input type="file" name="id_photo" accept="image/*" capture="user" required
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: rgba(253,246,236,0.7)">
                <p class="text-xs mt-1" style="color: rgba(253,246,236,0.4)">
                    📷 Prenez une photo en temps réel ou importez depuis votre galerie
                </p>
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° MVola</label>
                <input type="text" name="mvola_number" required value="{{ old('mvola_number') }}"
                       placeholder="034 XX XXX XX"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>

        @else
            {{-- FORMULAIRE ENTREPRISE --}}
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Type d'entreprise
                </label>
                <input type="text" name="business_type" required value="{{ old('business_type') }}"
                       placeholder="Ex: Boutique téléphonie, Cosmétique, Pharmacie..."
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Raison Sociale (Nom entreprise)
                </label>
                <input type="text" name="company_name" required value="{{ old('company_name') }}"
                       placeholder="Ex: SARL XYZ Madagascar"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Siège Social
                </label>
                <input type="text" name="address" required value="{{ old('address') }}"
                       placeholder="Ex: Lot 123, Analakely, Antananarivo"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">NIF</label>
                    <input type="text" name="nif" required value="{{ old('nif') }}"
                           placeholder="NIF"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">STAT</label>
                    <input type="text" name="stat" required value="{{ old('stat') }}"
                           placeholder="STAT"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                        Nom du Responsable
                    </label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                           placeholder="Nom"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                        Prénom
                    </label>
                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                           placeholder="Prénom"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">Genre</label>
                    <select name="gender" required
                            class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                            style="background: rgba(44,26,14,0.95); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                        <option value="">Choisir...</option>
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                        N° Téléphone
                    </label>
                    <input type="text" name="phone" required value="{{ old('phone') }}"
                           placeholder="034 XX XXX XX"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
            </div>
            <div class="reg-grid-2">
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° CIN</label>
                    <input type="text" name="cin_number" required value="{{ old('cin_number') }}"
                           placeholder="123 456 789 012"
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                        CIN (PDF)
                    </label>
                    <input type="file" name="cin_pdf" accept=".pdf" required
                           class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: rgba(253,246,236,0.7)">
                </div>
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">N° MVola</label>
                <input type="text" name="mvola_number" required value="{{ old('mvola_number') }}"
                       placeholder="034 XX XXX XX"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
        @endif

        {{-- Champs communs --}}
        <div>
            <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                Adresse Mail
            </label>
            <input type="email" name="email" required value="{{ old('email') }}"
                   placeholder="nom@exemple.mg"
                   class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
        </div>
        <div class="reg-grid-2">
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Mot de passe
                </label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
            <div>
                <label class="text-xs font-bold uppercase" style="color: rgba(244,164,41,0.8)">
                    Confirmer
                </label>
                <input type="password" name="password_confirmation" required
                       placeholder="••••••••"
                       class="w-full rounded-xl px-3 py-2 text-sm mt-1 focus:outline-none"
                       style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
            </div>
        </div>

        <button type="submit"
                class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest mt-2"
                style="background: #F4A429; color: #2C1A0E">
            Créer mon compte →
        </button>

        <p class="text-xs text-center" style="color: rgba(253,246,236,0.4)">
            En créant un compte vous acceptez notre
            <a href="{{ route('profile.edit') }}" style="color: #F4A429">politique de confidentialité</a>
        </p>
    </form>
    <style>
        .reg-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        @media (max-width: 480px) {
            .reg-grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</x-guest-layout>