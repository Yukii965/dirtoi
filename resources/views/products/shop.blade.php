<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Titre --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black"
                    style="font-family: 'Playfair Display', serif; color: #F4A429">
                    🛍️ Boutique
                </h1>
                <p class="mt-1 text-sm" style="color: rgba(253,246,236,0.5)">
                    {{ $products->total() }} produits disponibles
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Sidebar filtres --}}
                <div class="w-full lg:w-64 shrink-0">
                    <form method="GET" action="{{ route('products.index') }}"
                          class="space-y-4">

                        {{-- Recherche --}}
                        <div class="p-5 rounded-2xl"
                             style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3"
                                style="color: rgba(244,164,41,0.8)">Recherche</h3>
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Nom du produit..."
                                   class="w-full rounded-xl px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                        </div>

                        {{-- Catégories --}}
                        <div class="p-5 rounded-2xl"
                             style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3"
                                style="color: rgba(244,164,41,0.8)">Catégories</h3>
                            <div class="space-y-2">
                                <a href="{{ route('products.index') }}"
                                   class="block text-sm px-3 py-2 rounded-lg transition"
                                   style="color: {{ !request('category') ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                          background: {{ !request('category') ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                    Toutes les catégories
                                </a>
                                @foreach($categories as $cat)
                                    <a href="{{ route('products.index', ['category' => $cat->id]) }}"
                                       class="block text-sm px-3 py-2 rounded-lg transition"
                                       style="color: {{ request('category') == $cat->id ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                              background: {{ request('category') == $cat->id ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tri --}}
                        <div class="p-5 rounded-2xl"
                             style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3"
                                style="color: rgba(244,164,41,0.8)">Trier par</h3>
                            <select name="sort"
                                    class="w-full rounded-xl px-3 py-2 text-sm focus:outline-none"
                                    style="background: rgba(44,26,14,0.95); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                    Plus récents
                                </option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                    Prix croissant
                                </option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                    Prix décroissant
                                </option>
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full py-3 rounded-xl font-bold text-sm"
                                style="background: #F4A429; color: #2C1A0E">
                            🔍 Filtrer
                        </button>

                        @if(request('search') || request('category') || request('sort'))
                            <a href="{{ route('products.index') }}"
                               class="block text-center text-sm py-2"
                               style="color: rgba(253,246,236,0.4)">
                                ✕ Réinitialiser les filtres
                            </a>
                        @endif

                    </form>
                </div>

                {{-- Grille produits --}}
                <div class="flex-1">
                    @if($products->isEmpty())
                        <div class="text-center py-20 rounded-2xl"
                             style="background: rgba(44,26,14,0.5); border: 2px dashed rgba(244,164,41,0.2)">
                            <div class="text-6xl mb-4">🔍</div>
                            <p style="color: rgba(253,246,236,0.5)">
                                Aucun produit trouvé.
                            </p>
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
                                    <p class="text-xs mt-1" style="color: rgba(253,246,236,0.5)">
                                        Par {{ $product->user->name }}
                                    </p>
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
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>