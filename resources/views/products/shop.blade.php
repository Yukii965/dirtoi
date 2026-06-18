<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">

            {{-- En-tête --}}
            <div class="mb-8">
                <h1 class="text-2xl font-black" style="font-family:'Playfair Display',serif; color:#F4A429">
                    🏪 Boutiques GasyMarket
                </h1>
                <p class="text-sm mt-1" style="color: rgba(253,246,236,0.5)">
                    {{ $sellers->total() }} boutique(s) disponible(s)
                </p>
            </div>

            <div id="shop-layout" style="display:flex; gap:1.5rem; align-items:flex-start;">

                {{-- Sidebar filtres --}}
                <div id="shop-sidebar" style="width:260px; flex-shrink:0;">
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-4">

                        {{-- Recherche --}}
                        <div class="p-5 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3" style="color: rgba(244,164,41,0.8)">Recherche</h3>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Nom de la boutique..."
                                   class="w-full rounded-xl px-3 py-2 text-sm focus:outline-none"
                                   style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: #FDF6EC">
                        </div>

                        {{-- Type de vendeur --}}
                        <div class="p-5 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                            <h3 class="text-xs font-bold uppercase mb-3" style="color: rgba(244,164,41,0.8)">Type de vendeur</h3>
                            <div class="space-y-1">
                                <a href="{{ route('products.index', array_filter(['search' => request('search')])) }}"
                                   class="block text-sm px-3 py-2 rounded-lg transition"
                                   style="color: {{ !request('type') ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                          background: {{ !request('type') ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                    Tous les vendeurs
                                </a>
                                <a href="{{ route('products.index', array_filter(['search' => request('search'), 'type' => 'vendeur_pro'])) }}"
                                   class="block text-sm px-3 py-2 rounded-lg transition"
                                   style="color: {{ request('type') == 'vendeur_pro' ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                          background: {{ request('type') == 'vendeur_pro' ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                    🏢 Entreprises
                                </a>
                                <a href="{{ route('products.index', array_filter(['search' => request('search'), 'type' => 'vendeur_amateur'])) }}"
                                   class="block text-sm px-3 py-2 rounded-lg transition"
                                   style="color: {{ request('type') == 'vendeur_amateur' ? '#F4A429' : 'rgba(253,246,236,0.6)' }};
                                          background: {{ request('type') == 'vendeur_amateur' ? 'rgba(244,164,41,0.15)' : 'transparent' }}">
                                    👤 Particuliers
                                </a>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm" style="background:#F4A429; color:#2C1A0E">
                            🔍 Filtrer
                        </button>

                        @if(request('search') || request('type'))
                            <a href="{{ route('products.index') }}" class="block text-center text-sm py-2" style="color: rgba(253,246,236,0.4)">
                                ✕ Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Grille des boutiques --}}
                <div style="flex:1; min-width:0;">
                    @if($sellers->isEmpty())
                        <div class="text-center py-20 rounded-2xl" style="background: rgba(44,26,14,0.5); border: 2px dashed rgba(244,164,41,0.2)">
                            <div class="text-6xl mb-4">🔍</div>
                            <p style="color: rgba(253,246,236,0.5)">Aucune boutique trouvée.</p>
                        </div>
                    @else
                        {{-- #shops-grid : 1 colonne mobile, 2 en tablette, 3 en desktop
                             (géré ici via ID pour éviter les surcharges globales sur .lg\:grid-cols-X) --}}
                        <div id="shops-grid">
                            @foreach($sellers as $seller)
                                <a href="{{ route('shop.vendor', $seller->id) }}" class="gasy-card block p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0"
                                             style="background: rgba(244,164,41,0.15); border: 2px solid rgba(244,164,41,0.4); color:#F4A429">
                                            @if($seller->avatar)
                                                <img src="{{ asset('storage/' . $seller->avatar) }}" class="w-full h-full object-cover rounded-2xl" alt="{{ $seller->name }}">
                                            @else
                                                {{ strtoupper(substr($seller->company_name ?? $seller->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold truncate" style="color:#FDF6EC">
                                                {{ $seller->company_name ?? $seller->name }}
                                            </h3>
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-full inline-block mt-1"
                                                  style="background: {{ $seller->role === 'vendeur_pro' ? 'rgba(244,164,41,0.9)' : 'rgba(96,165,250,0.9)' }}; color:#2C1A0E">
                                                {{ $seller->role === 'vendeur_pro' ? '🏢 Entreprise' : '👤 Particulier' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-4 pt-4" style="border-top: 1px solid rgba(244,164,41,0.1)">
                                        <span class="text-sm" style="color: rgba(253,246,236,0.6)">
                                            {{ $seller->products_count }} produit(s)
                                        </span>
                                        <span class="text-xs font-bold" style="color:#F4A429">
                                            Voir la boutique →
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $sellers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* #shops-grid : 3 colonnes par défaut sur desktop */
        #shops-grid {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 1.25rem !important;
        }

        @media (max-width: 1024px) {
            /* Sidebar passe au-dessus, en pleine largeur */
            #shop-layout  { flex-direction: column !important; }
            #shop-sidebar { width: 100% !important; }

            /* 2 colonnes en tablette */
            #shops-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }

        @media (max-width: 640px) {
            /* 1 colonne en mobile */
            #shops-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</x-app-layout>