<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">

            {{-- En-tête --}}
            <div class="mb-10">
                <h1 class="text-3xl font-black"
                    style="font-family: 'Playfair Display', serif; color: #F4A429">
                    🏪 Marketplace
                </h1>
                <p class="mt-1 text-sm" style="color: rgba(253,246,236,0.5)">
                    {{ $sellers->total() }} boutiques disponibles
                </p>
            </div>

            {{-- Barre de recherche vendeur --}}
            <form method="GET" action="{{ route('products.index') }}" class="mb-8">
                <div class="flex gap-3">
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher une boutique ou un vendeur..."
                           class="flex-1 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2"
                           style="background: rgba(44,26,14,0.85);
                                  border: 1px solid rgba(244,164,41,0.3);
                                  color: #FDF6EC;
                                  focus-ring-color: #F4A429">
                    <button type="submit"
                            class="px-6 py-3 rounded-xl font-bold text-sm"
                            style="background: #F4A429; color: #2C1A0E">
                        🔍 Rechercher
                    </button>
                    @if(request('search'))
                        <a href="{{ route('products.index') }}"
                           class="px-4 py-3 rounded-xl text-sm flex items-center"
                           style="background: rgba(44,26,14,0.85);
                                  border: 1px solid rgba(244,164,41,0.2);
                                  color: rgba(253,246,236,0.5)">
                            ✕
                        </a>
                    @endif
                </div>
            </form>

            {{-- Filtres par type de vendeur --}}
            <div class="flex gap-3 mb-8 flex-wrap">
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold transition"
                   style="background: {{ !request('type') ? '#F4A429' : 'rgba(44,26,14,0.85)' }};
                          color: {{ !request('type') ? '#2C1A0E' : 'rgba(253,246,236,0.7)' }};
                          border: 1px solid rgba(244,164,41,0.3)">
                    Tous les vendeurs
                </a>
                <a href="{{ route('products.index', ['type' => 'vendeur_pro']) }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold transition"
                   style="background: {{ request('type') === 'vendeur_pro' ? '#F4A429' : 'rgba(44,26,14,0.85)' }};
                          color: {{ request('type') === 'vendeur_pro' ? '#2C1A0E' : 'rgba(253,246,236,0.7)' }};
                          border: 1px solid rgba(244,164,41,0.3)">
                    🏢 Entreprises Pro
                </a>
                <a href="{{ route('products.index', ['type' => 'vendeur_amateur']) }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold transition"
                   style="background: {{ request('type') === 'vendeur_amateur' ? '#F4A429' : 'rgba(44,26,14,0.85)' }};
                          color: {{ request('type') === 'vendeur_amateur' ? '#2C1A0E' : 'rgba(253,246,236,0.7)' }};
                          border: 1px solid rgba(244,164,41,0.3)">
                    👤 Vendeurs Particuliers
                </a>
            </div>

            {{-- Grille des vendeurs --}}
            @if($sellers->isEmpty())
                <div class="text-center py-20 rounded-2xl"
                     style="background: rgba(44,26,14,0.5);
                            border: 2px dashed rgba(244,164,41,0.2)">
                    <div class="text-6xl mb-4">🔍</div>
                    <p style="color: rgba(253,246,236,0.5)">
                        Aucune boutique trouvée.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sellers as $seller)
                        <a href="{{ route('shop.vendor', $seller->id) }}"
                           class="group block rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1"
                           style="background: rgba(44,26,14,0.85);
                                  border: 1px solid rgba(244,164,41,0.2);
                                  box-shadow: 0 4px 20px rgba(0,0,0,0.3)">

                            {{-- Bannière / Avatar --}}
                            <div class="relative h-32 flex items-center justify-center"
                                 style="background: linear-gradient(135deg, rgba(244,164,41,0.15), rgba(44,26,14,0.9))">

                                {{-- Badge type vendeur --}}
                                <span class="absolute top-3 right-3 text-xs font-bold px-2 py-1 rounded-full"
                                      style="background: {{ $seller->role === 'vendeur_pro' ? 'rgba(244,164,41,0.9)' : 'rgba(96,165,250,0.9)' }};
                                             color: #2C1A0E">
                                    {{ $seller->role === 'vendeur_pro' ? '🏢 Pro' : '👤 Amateur' }}
                                </span>

                                {{-- Avatar initiales --}}
                                <div class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-black border-4 transition-transform duration-300 group-hover:scale-110"
                                     style="background: rgba(244,164,41,0.2);
                                            border-color: rgba(244,164,41,0.4);
                                            color: #F4A429">
                                    @if($seller->avatar)
                                        <img src="{{ asset('storage/' . $seller->avatar) }}"
                                             class="w-full h-full object-cover rounded-full"
                                             alt="{{ $seller->name }}">
                                    @else
                                        {{ strtoupper(substr($seller->company_name ?? $seller->name, 0, 1)) }}
                                    @endif
                                </div>
                            </div>

                            {{-- Infos vendeur --}}
                            <div class="p-5">
                                <h3 class="font-black text-lg leading-tight group-hover:text-yellow-400 transition"
                                    style="color: #FDF6EC; font-family: 'Playfair Display', serif">
                                    {{ $seller->company_name ?? $seller->name }}
                                </h3>

                                @if($seller->company_name)
                                    <p class="text-xs mt-0.5" style="color: rgba(253,246,236,0.45)">
                                        {{ $seller->name }}
                                    </p>
                                @endif

                                {{-- Stats --}}
                                <div class="flex items-center gap-4 mt-4">
                                    <div class="text-center">
                                        <p class="text-lg font-black" style="color: #F4A429">
                                            {{ $seller->products_count ?? 0 }}
                                        </p>
                                        <p class="text-xs" style="color: rgba(253,246,236,0.4)">
                                            produits
                                        </p>
                                    </div>
                                    <div class="h-8 w-px" style="background: rgba(244,164,41,0.2)"></div>
                                    <div class="text-center">
                                        <p class="text-lg font-black" style="color: #F4A429">
                                            {{ $seller->categories_count ?? 0 }}
                                        </p>
                                        <p class="text-xs" style="color: rgba(253,246,236,0.4)">
                                            catégories
                                        </p>
                                    </div>
                                    <div class="flex-1 flex justify-end">
                                        <span class="text-xs px-2 py-1 rounded-full"
                                              style="background: rgba(34,197,94,0.15); color: #4ade80">
                                            ● Actif
                                        </span>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                <div class="mt-4 pt-4 flex items-center justify-between"
                                     style="border-top: 1px solid rgba(244,164,41,0.1)">
                                    <span class="text-xs" style="color: rgba(253,246,236,0.4)">
                                        Voir la boutique
                                    </span>
                                    <span style="color: #F4A429">→</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $sellers->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>