<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-6">

            <h1 class="text-3xl font-black mb-8 text-center"
                style="font-family: 'Playfair Display', serif; color: #F4A429">
                Sélection du mode de règlement
            </h1>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl"
                     style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3)">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-sm">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Total --}}
                <div class="p-6 rounded-2xl"
                     style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="flex justify-between items-center">
                        <span style="color: rgba(253,246,236,0.6)">Total à payer</span>
                        <span class="text-3xl font-black" style="color: #F4A429">
                            {{ number_format($total, 0, ',', ' ') }} Ar
                        </span>
                    </div>
                    <p class="text-xs mt-2" style="color: rgba(253,246,236,0.4)">
                        🔒 Votre argent sera sécurisé jusqu'à confirmation de réception
                    </p>
                </div>

                {{-- Livraison --}}
                <div class="p-6 rounded-2xl space-y-4"
                     style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                    <h3 class="text-xs font-bold uppercase tracking-widest"
                        style="color: rgba(244,164,41,0.8)">
                        Coordonnées de livraison
                    </h3>
                    <input type="text" name="address" value="{{ old('address') }}"
                           placeholder="Adresse exacte (Ex: Logement 123, Itaosy)" required
                           class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="Numéro de téléphone (Ex: 034 XX XXX XX)" required
                           class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                </div>

                {{-- MVola --}}
                <div class="p-6 rounded-2xl space-y-4"
                     style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <h3 class="text-xs font-bold uppercase tracking-widest"
                        style="color: rgba(244,164,41,0.8)">
                        📱 Paiement via MVola
                    </h3>
                    <input type="text" name="mvola_number" value="{{ old('mvola_number') }}"
                           placeholder="Votre numéro MVola (Ex: 034 XX XXX XX)" required
                           class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                    <div class="text-xs space-y-1" style="color: rgba(253,246,236,0.4)">
                        <p>1. Entrez votre numéro MVola</p>
                        <p>2. Confirmez la commande</p>
                        <p>3. Validez le paiement sur votre téléphone</p>
                        <p>4. Votre argent est retenu jusqu'à la livraison</p>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest"
                        style="background: #F4A429; color: #2C1A0E">
                    🔒 Sécuriser le paiement
                </button>

                <a href="{{ route('cart.index') }}"
                   class="block text-center text-sm" style="color: rgba(253,246,236,0.4)">
                    ← Retour au panier
                </a>
            </form>
        </div>
    </div>
</x-app-layout>