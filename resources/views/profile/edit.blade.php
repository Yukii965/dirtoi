<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-6 space-y-6">

            {{-- Titre --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black"
                    style="font-family: 'Playfair Display', serif; color: #F4A429">
                    Mon profil
                </h1>
            </div>

            {{-- Photo de profil --}}
            <div class="p-6 rounded-2xl text-center"
                 style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase mb-4" style="color: rgba(244,164,41,0.8)">
                    Photo de profil
                </h3>

                {{-- Avatar actuel --}}
                <div class="flex justify-center mb-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             class="w-24 h-24 rounded-full object-cover"
                             style="border: 3px solid #F4A429">
                    @else
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-black"
                             style="background: linear-gradient(135deg, #F4A429, #E07B2A); color: #2C1A0E; border: 3px solid #F4A429">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                {{-- Formulaire upload --}}
                <form action="{{ route('profile.avatar') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="file" name="avatar" accept="image/*"
                           class="w-full rounded-xl px-4 py-3 text-sm mb-3 focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: rgba(253,246,236,0.7)">
                    <button type="submit"
                            class="w-full py-3 rounded-xl font-bold text-sm"
                            style="background: rgba(244,164,41,0.15); border: 1px solid rgba(244,164,41,0.3); color: #F4A429">
                        📷 Mettre à jour la photo
                    </button>
                </form>
            </div>

            {{-- Informations personnelles --}}
            <div class="p-6 rounded-2xl"
                 style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase mb-4" style="color: rgba(244,164,41,0.8)">
                    Informations personnelles
                </h3>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Mot de passe --}}
            <div class="p-6 rounded-2xl"
                 style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase mb-4" style="color: rgba(244,164,41,0.8)">
                    Changer le mot de passe
                </h3>
                @include('profile.partials.update-password-form')
            </div>

            {{-- Politique de confidentialité --}}
            <div class="p-6 rounded-2xl"
                 style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-sm font-bold uppercase mb-3" style="color: rgba(244,164,41,0.8)">
                    🔒 Politique de confidentialité
                </h3>
                <div class="text-sm space-y-2" style="color: rgba(253,246,236,0.6)">
                    <p>✅ Vos données personnelles sont chiffrées et sécurisées.</p>
                    <p>✅ Nous ne vendons jamais vos informations à des tiers.</p>
                    <p>✅ Vos transactions sont protégées par notre système Escrow.</p>
                    <p>✅ Vous pouvez demander la suppression de votre compte à tout moment.</p>
                    <p class="mt-3">
                        Pour toute question :
                        <a href="mailto:support@gasymarket.mg"
                           style="color: #F4A429" class="hover:underline">
                            support@gasymarket.mg
                        </a>
                    </p>
                </div>
            </div>

            {{-- Supprimer le compte --}}
            <div class="p-6 rounded-2xl"
                 style="background: rgba(44,26,14,0.85); border: 1px solid rgba(239,68,68,0.2)">
                <h3 class="text-sm font-bold uppercase mb-4" style="color: rgba(239,68,68,0.7)">
                    ⚠️ Zone dangereuse
                </h3>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>