<x-app-layout>
    <div class="py-12">
        {{-- max-w-2xl et space-y-6 remplacés par styles inline (absents du build) --}}
        <div style="max-width: 42rem; margin: 0 auto; padding: 0 1.5rem;
                    display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Titre --}}
            <div style="text-align:center; margin-bottom:0.5rem;">
                <h1 class="text-3xl font-black"
                    style="font-family: 'Playfair Display', serif; color: #F4A429">
                    Mon profil
                </h1>
            </div>

            {{-- Photo de profil --}}
            <div style="padding:1.5rem; border-radius:1rem; text-align:center;
                        background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase"
                    style="color: rgba(244,164,41,0.8); margin-bottom:1rem;">
                    Photo de profil
                </h3>

                {{-- Avatar --}}
                <div style="display:flex; justify-content:center; margin-bottom:1rem;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             style="width:6rem; height:6rem; border-radius:50%; object-fit:cover;
                                    border: 3px solid #F4A429;"
                             alt="Avatar">
                    @else
                        <div style="width:6rem; height:6rem; border-radius:50%;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:1.875rem; font-weight:900; color:#2C1A0E;
                                    background: linear-gradient(135deg, #F4A429, #E07B2A);
                                    border: 3px solid #F4A429;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <form action="{{ route('profile.avatar') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="file" name="avatar" accept="image/*"
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                  font-size:0.875rem; margin-bottom:0.75rem;
                                  background: rgba(255,255,255,0.08);
                                  border: 1px solid rgba(244,164,41,0.2);
                                  color: rgba(253,246,236,0.7); box-sizing:border-box;">
                    <button type="submit"
                            style="width:100%; padding:0.75rem; border-radius:0.75rem;
                                   font-weight:700; font-size:0.875rem;
                                   background: rgba(244,164,41,0.15);
                                   border: 1px solid rgba(244,164,41,0.3);
                                   color: #F4A429; cursor:pointer;">
                        📷 Mettre à jour la photo
                    </button>
                </form>
            </div>

            {{-- Informations personnelles --}}
            <div style="padding:1.5rem; border-radius:1rem;
                        background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase"
                    style="color: rgba(244,164,41,0.8); margin-bottom:1rem;">
                    Informations personnelles
                </h3>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Mot de passe --}}
            <div style="padding:1.5rem; border-radius:1rem;
                        background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase"
                    style="color: rgba(244,164,41,0.8); margin-bottom:1rem;">
                    Changer le mot de passe
                </h3>
                @include('profile.partials.update-password-form')
            </div>

            {{-- Politique de confidentialité --}}
            <div style="padding:1.5rem; border-radius:1rem;
                        background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase"
                    style="color: rgba(244,164,41,0.8); margin-bottom:0.75rem;">
                    🔒 Politique de confidentialité
                </h3>
                <div style="font-size:0.875rem; display:flex; flex-direction:column; gap:0.5rem;
                            color: rgba(253,246,236,0.6)">
                    <p>✅ Vos données personnelles sont chiffrées et sécurisées.</p>
                    <p>✅ Nous ne vendons jamais vos informations à des tiers.</p>
                    <p>✅ Vos transactions sont protégées par notre système Escrow.</p>
                    <p>✅ Vous pouvez demander la suppression de votre compte à tout moment.</p>
                    <p style="margin-top:0.75rem;">
                        Pour toute question :
                        <a href="mailto:support@gasymarket.mg"
                           style="color: #F4A429; text-decoration:none;">
                            support@gasymarket.mg
                        </a>
                    </p>
                </div>
            </div>

            {{-- Zone dangereuse --}}
            <div style="padding:1.5rem; border-radius:1rem;
                        background: rgba(44,26,14,0.85); border: 1px solid rgba(239,68,68,0.2)">
                <h3 class="text-sm font-bold uppercase"
                    style="color: rgba(239,68,68,0.7); margin-bottom:1rem;">
                    ⚠️ Zone dangereuse
                </h3>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

    <style>
        @media (max-width: 640px) {
            input[type="text"],
            input[type="email"],
            input[type="password"] { font-size: 16px !important; }
        }
    </style>
</x-app-layout>