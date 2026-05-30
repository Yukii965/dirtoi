<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-6">

            {{-- Lien retour --}}
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-sm mb-8 hover:underline"
               style="color: rgba(244,164,41,0.7)">
                ← Retour à la boutique
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- Image produit --}}
                <div class="rounded-2xl overflow-hidden"
                     style="border: 1px solid rgba(244,164,41,0.2)">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="w-full h-96 object-cover"
                         alt="{{ $product->name }}">
                </div>

                {{-- Infos produit --}}
                <div class="space-y-6">

                    {{-- Catégorie --}}
                    <span class="text-xs font-bold px-3 py-1 rounded-full"
                          style="background: rgba(244,164,41,0.15); color: #F4A429">
                        {{ $product->category->name }}
                    </span>

                    {{-- Nom --}}
                    <h1 class="text-3xl font-black mt-3"
                        style="font-family: 'Playfair Display', serif; color: #FDF6EC">
                        {{ $product->name }}
                    </h1>

                    {{-- Vendeur --}}
                    <p class="text-sm" style="color: rgba(253,246,236,0.5)">
                        Vendu par
                        <span class="font-bold" style="color: rgba(253,246,236,0.8)">
                            {{ $product->user->name }}
                        </span>
                        @if($product->user->isVendeurPro())
                            <span class="ml-1 text-xs px-2 py-0.5 rounded-full"
                                  style="background: rgba(34,197,94,0.2); color: #4ade80">
                                Entreprise ✓
                            </span>
                        @endif
                    </p>

                    {{-- Prix --}}
                    <div class="py-4" style="border-top: 1px solid rgba(244,164,41,0.1); border-bottom: 1px solid rgba(244,164,41,0.1)">
                        <span class="text-4xl font-black" style="color: #F4A429">
                            {{ number_format($product->price, 0, ',', ' ') }} Ar
                        </span>
                    </div>

                    {{-- Description --}}
                    <p class="text-sm leading-relaxed" style="color: rgba(253,246,236,0.7)">
                        {{ $product->description }}
                    </p>

                    {{-- Stock --}}
                    <p class="text-sm" style="color: rgba(253,246,236,0.5)">
                        Stock disponible :
                        <span class="font-bold" style="color: {{ $product->stock > 0 ? '#4ade80' : '#f87171' }}">
                            {{ $product->stock > 0 ? $product->stock . ' unités' : 'Rupture de stock' }}
                        </span>
                    </p>

                    {{-- Bouton ajouter au panier --}}
                    @auth
                        @if(auth()->user()->isAcheteur() && $product->stock > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest transition-all active:scale-95"
                                        style="background: #F4A429; color: #2C1A0E">
                                    🛒 Ajouter au panier
                                </button>
                            </form>
                        @elseif(!auth()->user()->isAcheteur())
                            <p class="text-center text-sm py-3 rounded-xl"
                               style="background: rgba(244,164,41,0.1); color: rgba(253,246,236,0.5)">
                                Seuls les acheteurs peuvent commander
                            </p>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="block w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest text-center transition-all"
                           style="background: #F4A429; color: #2C1A0E">
                            Se connecter pour acheter
                        </a>
                    @endauth

                    {{-- Sécurité --}}
                    <p class="text-xs text-center" style="color: rgba(253,246,236,0.3)">
                        🔒 Paiement sécurisé — Argent retenu jusqu'à livraison confirmée
                    </p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>