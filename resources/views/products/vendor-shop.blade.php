<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Fil d'Ariane --}}
            <nav class="flex items-center gap-2 text-xs mb-8" style="color: rgba(253,246,236,0.4)">
                <a href="{{ route('products.index') }}"
                   class="hover:text-yellow-400 transition">
                    🏪 Marketplace
                </a>
                <span>›</span>
                <span style="color: #F4A429">
                    {{ $seller->company_name ?? $seller->name }}
                </span>
            </nav>

            {{-- Profil vendeur --}}
            <div class="rounded-2xl p-6 mb-8 relative overflow-hidden"
                 style="background: rgba(44,26,14,0.85);
                        border: 1px solid rgba(244,164,41,0.25);
                        box-shadow: 0 8px 32px rgba(0,0,0,0.4)">

                {{-- Fond décoratif --}}
                <div class="absolute inset-0 opacity-5"
                     style="background: radial-gradient(circle at 80% 50%, #F4A429 0%, transparent 60%)">
                </div>

                <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-6">

                    {{-- Avatar --}}
                    <div class="w-24 h-24 rounded-2xl flex items-center justify-center text-3xl font-black shrink-0"
                         style="background: rgba(244,164,41,0.15);
                                border: 2px solid rgba(244,164,41,0.4);
                                color: #F4A429">
                        @if($seller->avatar)
                            <img src="{{ asset('storage/' . $seller->avatar) }}"
                                 class="w-full h-full object-cover rounded-2xl"
                                 alt="{{ $seller->name }}">
                        @else
                            {{ strtoupper(substr($seller->company_name ?? $seller->name, 0, 1)) }}
                        @endif
                    </div>

                    {{-- Infos --}}
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-1">
                            <h1 class="text-2xl font-black"
                                style="font-family: 'Playfair Display', serif; color: #FDF6EC">
                                {{ $seller->company_name ?? $seller->name }}
                            </h1>
                            <span class="text-xs font-bold px-3 py-1 rounded-full"
                                  style="background: {{ $seller->role === 'vendeur_pro' ? 'rgba(244,164,41,0.9)' : 'rgba(96,165,250,0.9)' }};
                                         color: #2C1A0E">
                                {{ $seller->role === 'vendeur_pro' ? '🏢 Entreprise Pro' : '👤 Vendeur Particulier' }}
                            </span>
                        </div>

                        @if($seller->company_name)
                            <p class="text-sm mb-3" style="color: rgba(253,246,236,0.5)">
                                Gérant : {{ $seller->name }}
                            </p>
                        @endif

                        {{-- Stats rapides --}}
                        <div class="flex flex-wrap gap-6">
                            <div>
                                <span class="text-xl font-black" style="color: #F4A429">
                                    {{ $totalProducts }}
                                </span>
                                <span class="text-xs ml-1" style="color: rgba(253,246,236,0.4)">
                                    produits
                                </span>
                            </div>
                            <div>
                                <span class="text-xl font-black" style="color: #F4A429">
                                    {{ $sellerCategories->count() }}
                                </span>
                                <span class="text-xs ml-1" style="color: rgba(253,246,236,0.4)">
                                    catégories
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full" style="background: #4ade80"></span>
                                <span class="text-xs" style="color: #4ade80">Boutique active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Sidebar : catégories dynamiques du vendeur --}}
                <div class="w-full lg:w-64 shrink-0">
                    <form method="GET" action="{{ route('shop.vendor', $seller->id) }}"
                          class="space-y-4">

                        {{-- Recherche --}}
                        <div class="p-5 rounded-2xl"
                             style="background: rgba(44,26,14,0.85);
                                    border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3"
                                style="color: rgba(244,164,41,0.8)">Recherche</h3>
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Dans cette boutique..."
                                   class="w-full rounded-xl px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.08);
                                          border: 1px solid rgba(244,164,41,0.2);
                                          color: #FDF6EC">
                        </div>

                        {{-- Catégories DYNAMIQUES du vendeur --}}
                        <div class="p-5 rounded-2xl"
                             style="background: rgba(44,26,14,0.85);
                                    border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3"
                                style="color: rgba(244,164,41,0.8)">Catégories</h3>

                            @if($sellerCategories->isEmpty())
                                <p class="text-xs" style="color: rgba(253,246,236,0.3)">
                                    Aucune catégorie
                                </p>
                            @else
                                <div class="space-y-1">
                                    <a href="{{ route('shop.vendor', $seller->id) }}"
                                       class="flex items-center justify-between text-sm px-3 py-2 rounded-lg transition"
                                       style="color: {{ !request('category') ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                              background: {{ !request('category') ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                        <span>Tous les produits</span>
                                        <span class="text-xs px-1.5 py-0.5 rounded-full"
                                              style="background: rgba(244,164,41,0.15); color: #F4A429">
                                            {{ $totalProducts }}
                                        </span>
                                    </a>

                                    @foreach($sellerCategories as $cat)
                                        <a href="{{ route('shop.vendor', ['vendor' => $seller->id, 'category' => $cat->id]) }}"
                                           class="flex items-center justify-between text-sm px-3 py-2 rounded-lg transition"
                                           style="color: {{ request('category') == $cat->id ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                                  background: {{ request('category') == $cat->id ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                            <span>{{ $cat->name }}</span>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full"
                                                  style="background: rgba(244,164,41,0.1); color: rgba(244,164,41,0.7)">
                                                {{ $cat->products_count }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Tri --}}
                        <div class="p-5 rounded-2xl"
                             style="background: rgba(44,26,14,0.85);
                                    border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3"
                                style="color: rgba(244,164,41,0.8)">Trier par</h3>
                            <select name="sort"
                                    class="w-full rounded-xl px-3 py-2 text-sm focus:outline-none"
                                    style="background: rgba(44,26,14,0.95);
                                           border: 1px solid rgba(244,164,41,0.2);
                                           color: #FDF6EC">
                                <option value="newest"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Plus récents</option>
                                <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full py-3 rounded-xl font-bold text-sm"
                                style="background: #F4A429; color: #2C1A0E">
                            🔍 Filtrer
                        </button>

                        @if(request('search') || request('category') || request('sort'))
                            <a href="{{ route('shop.vendor', $seller->id) }}"
                               class="block text-center text-sm py-2"
                               style="color: rgba(253,246,236,0.4)">
                                ✕ Réinitialiser
                            </a>
                        @endif

                    </form>
                </div>

                {{-- Grille produits du vendeur --}}
                <div class="flex-1">

                    {{-- Titre section --}}
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-bold text-lg" style="color: #FDF6EC">
                            @if(request('category'))
                                {{ $sellerCategories->firstWhere('id', request('category'))?->name ?? 'Produits' }}
                            @else
                                Tous les produits
                            @endif
                        </h2>
                        <span class="text-sm" style="color: rgba(253,246,236,0.4)">
                            {{ $products->total() }} résultat(s)
                        </span>
                    </div>

                    @if($products->isEmpty())
                        <div class="text-center py-20 rounded-2xl"
                             style="background: rgba(44,26,14,0.5);
                                    border: 2px dashed rgba(244,164,41,0.2)">
                            <div class="text-6xl mb-4">🔍</div>
                            <p style="color: rgba(253,246,236,0.5)">
                                Aucun produit trouvé dans cette boutique.
                            </p>
                            <a href="{{ route('shop.vendor', $seller->id) }}"
                               class="inline-block mt-4 px-4 py-2 rounded-xl text-sm font-bold"
                               style="background: rgba(244,164,41,0.15); color: #F4A429">
                                Voir tous les produits
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="gasy-card group overflow-hidden block">

                                    <div class="overflow-hidden h-48 relative">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                             alt="{{ $product->name }}">
                                        <span class="absolute top-3 left-3 text-xs font-bold px-2 py-1 rounded-full"
                                              style="background: rgba(244,164,41,0.9); color: #2C1A0E">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>

                                    <div class="p-4">
                                        <h3 class="font-bold group-hover:text-yellow-400 transition"
                                            style="color: #FDF6EC">
                                            {{ $product->name }}
                                        </h3>
                                        <div class="flex items-center justify-between mt-4">
                                            <span class="text-xl font-black" style="color: #F4A429">
                                                {{ number_format($product->price, 0, ',', ' ') }} Ar
                                            </span>
                                            <span class="text-xs px-2 py-1 rounded-full"
                                                  style="background: rgba(34,197,94,0.15); color: #4ade80">
                                                Stock: {{ $product->stock }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>