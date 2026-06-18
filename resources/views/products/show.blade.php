<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-6">

            {{-- Lien retour --}}
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 text-sm mb-8 hover:underline"
               style="color: rgba(244,164,41,0.7)">
                ← Retour à la boutique
            </a>

            <div id="product-detail-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:start;">

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
                            <a href="{{ route('vendor.shop', $product->user->id) }}" style="color:rgba(253,246,236,0.8);text-decoration:none" onmouseover="this.style.color='#F4A429'" onmouseout="this.style.color='rgba(253,246,236,0.8)'">
                                {{ $product->user->name }}
                            </a>
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

                    {{-- Actions selon le rôle --}}
                    @auth
                        @if(auth()->user()->isAcheteur() && $product->stock > 0)
                            {{-- Acheteur → Panier --}}
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        style="width:100%; padding:1rem; border-radius:0.75rem; font-weight:900;
                                            font-size:0.875rem; text-transform:uppercase; letter-spacing:0.05em;
                                            background:#F4A429; color:#2C1A0E; border:none; cursor:pointer;">
                                    🛒 Ajouter au panier
                                </button>
                            </form>

                        @elseif(auth()->user()->isAcheteur() && $product->stock <= 0)
                            {{-- Acheteur → Rupture --}}
                            <button disabled
                                    style="width:100%; padding:1rem; border-radius:0.75rem; font-weight:900;
                                        font-size:0.875rem; text-transform:uppercase;
                                        background:rgba(255,255,255,0.1); color:rgba(253,246,236,0.4); cursor:not-allowed; border:none;">
                                Rupture de stock
                            </button>

                        @elseif(auth()->user()->isAdmin())
                            {{-- Admin → Valider / Suspendre --}}
                            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                                @if($product->product_status === 'en_attente')
                                    <form action="{{ route('admin.product.approve', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                style="width:100%; padding:0.75rem; border-radius:0.75rem; font-weight:700;
                                                    font-size:0.875rem; background:rgba(34,197,94,0.15); color:#4ade80;
                                                    border:1px solid rgba(34,197,94,0.3); cursor:pointer;">
                                            ✅ Valider ce produit
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.product.suspend', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            style="width:100%; padding:0.75rem; border-radius:0.75rem; font-weight:700;
                                                font-size:0.875rem; background:rgba(239,68,68,0.1); color:#f87171;
                                                border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                                        🚫 Suspendre ce produit
                                    </button>
                                </form>
                            </div>

                        @elseif(auth()->user()->isVendeur() && auth()->id() === $product->user_id)
                            {{-- Vendeur propriétaire → Modifier / Supprimer --}}
                            <div style="display:flex; gap:0.75rem;">
                                <a href="{{ route('vendor.product.edit', $product->id) }}"
                                style="flex:1; padding:0.75rem; border-radius:0.75rem; font-weight:700;
                                        font-size:0.875rem; text-align:center; text-decoration:none;
                                        background:rgba(244,164,41,0.15); color:#F4A429;
                                        border:1px solid rgba(244,164,41,0.3);">
                                    ✏️ Modifier
                                </a>
                                <form action="{{ route('vendor.product.delete', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce produit définitivement ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="padding:0.75rem 1.25rem; border-radius:0.75rem; font-weight:700;
                                                font-size:0.875rem; background:rgba(239,68,68,0.1); color:#f87171;
                                                border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>

                        @else
                            {{-- Autre vendeur → ne peut pas acheter --}}
                            <p style="text-align:center; font-size:0.875rem; padding:0.75rem; border-radius:0.75rem;
                                    background:rgba(244,164,41,0.1); color:rgba(253,246,236,0.5);">
                                Seuls les acheteurs peuvent commander
                            </p>
                        @endif

                    @else
                        {{-- Non connecté --}}
                        <a href="{{ route('login') }}"
                        style="display:block; width:100%; padding:1rem; border-radius:0.75rem; font-weight:900;
                                font-size:0.875rem; text-transform:uppercase; letter-spacing:0.05em; text-align:center;
                                text-decoration:none; background:#F4A429; color:#2C1A0E;">
                            Se connecter pour commander
                        </a>
                    @endauth

                    {{-- Bouton Contacter le vendeur — SÉPARÉ du bloc @auth ci-dessus
                        pour éviter tout formulaire imbriqué.
                        Visible uniquement pour les acheteurs, pas pour le vendeur lui-même. --}}
                    @auth
                        @if(auth()->user()->isAcheteur() && $product->user_id !== auth()->id())
                            <form action="{{ route('messages.start', $product) }}" method="POST"
                                style="margin-top:0.75rem;">
                                @csrf
                                <button type="submit"
                                        style="width:100%; padding:0.875rem; border-radius:0.75rem; font-weight:700;
                                            font-size:0.875rem; cursor:pointer;
                                            background:rgba(244,164,41,0.08);
                                            border:1px solid rgba(244,164,41,0.35);
                                            color:#F4A429;">
                                    💬 Contacter le vendeur
                                </button>
                            </form>
                        @endif
                    @endauth

                    {{-- Sécurité --}}
                    <p class="text-xs text-center" style="color: rgba(253,246,236,0.3)">
                        🔒 Paiement sécurisé — Argent retenu jusqu'à livraison confirmée
                    </p>

                </div>
            </div>
        </div>
    </div>
    <style>
        #product-detail-grid { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 2rem !important; }
        @media (max-width: 900px) {
            #product-detail-grid { grid-template-columns: 1fr !important; }
            #product-detail-grid > div:first-child { height: clamp(220px, 50vw, 380px) !important; }
            #product-detail-grid > div:first-child img { height: 100% !important; }
            #similar-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 480px) {
            #similar-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
    </style>
</x-app-layout>