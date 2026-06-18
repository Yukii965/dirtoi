@php
    $custom     = $customization ?? $seller->shopCustomization;
    $shopColor  = $custom?->primary_color ?? '#F4A429';
    $shopBanner = $custom?->banner_image;
    $shopBio    = $custom?->bio;
    $shopBlocks = $custom?->blocks ?? [];
    $isOwner    = $isOwner ?? false;
    $isPremium  = $isPremium ?? false;
@endphp

<x-app-layout>

{{-- Couleur dynamique de la boutique --}}
<style id="shop-color-style">
    :root { --shop-color: {{ $shopColor }}; }
    .shop-primary { color: var(--shop-color) !important; }
    .shop-bg      { background: var(--shop-color) !important; }
    .shop-border  { border-color: var(--shop-color) !important; }
</style>

{{-- Barre d'édition (vendeur propriétaire uniquement) --}}
@if($isOwner)
<div id="edit-bar"
     style="position:sticky; top:0; z-index:300; padding:0.6rem 1.5rem;
            background:rgba(44,26,14,0.97); border-bottom:2px solid var(--shop-color);
            display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
    <span style="font-size:0.8rem; font-weight:700; color:var(--shop-color);">
        ✏️ Mode édition
    </span>
    <span style="font-size:0.75rem; color:rgba(253,246,236,0.5);">
        Glissez-déposez vos produits pour les réorganiser
    </span>
    <div style="margin-left:auto; display:flex; gap:0.5rem; align-items:center;">
        <span id="save-status" style="font-size:0.75rem; color:#4ade80; display:none;">
            ✅ Sauvegardé
        </span>
        <button onclick="openPanel()"
                style="padding:0.4rem 1rem; border-radius:0.5rem; font-size:0.8rem;
                       font-weight:700; cursor:pointer; border:none;
                       background:var(--shop-color); color:#2C1A0E;">
            ⚙️ Personnaliser
        </button>
        <a href="{{ route('home') }}"
           style="padding:0.4rem 0.75rem; border-radius:0.5rem; font-size:0.75rem;
                  text-decoration:none; color:rgba(253,246,236,0.5);
                  border:1px solid rgba(255,255,255,0.1);">
            Quitter l'édition
        </a>
    </div>
</div>

{{-- Panneau de personnalisation (slide-in droite) --}}
<div id="edit-panel"
     style="position:fixed; top:0; right:-360px; width:340px; height:100vh;
            z-index:500; overflow-y:auto; transition:right 0.3s ease;
            background:rgba(30,15,5,0.98); border-left:1px solid var(--shop-color);
            padding:1.5rem; display:flex; flex-direction:column; gap:1.25rem;">

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="color:var(--shop-color); font-weight:900; font-size:1rem;
                   font-family:'Playfair Display',serif;">
            ⚙️ Personnalisation
        </h2>
        <button onclick="closePanel()"
                style="background:none; border:none; color:rgba(253,246,236,0.5);
                       cursor:pointer; font-size:1.25rem;">✕</button>
    </div>

    {{-- Couleur principale --}}
    <div style="padding:1rem; border-radius:0.75rem;
                background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
        <label style="display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase;
                      color:rgba(253,246,236,0.5); margin-bottom:0.75rem;">
            🎨 Couleur de votre boutique
        </label>
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <input type="color" id="color-picker" value="{{ $shopColor }}"
                   oninput="onColorChange(this.value)"
                   style="width:3rem; height:3rem; border-radius:0.5rem; cursor:pointer;
                          border:2px solid rgba(255,255,255,0.15); background:none;">
            <span id="color-hex-display"
                  style="font-family:monospace; font-size:0.875rem; color:rgba(253,246,236,0.7);">
                {{ $shopColor }}
            </span>
        </div>
    </div>

    {{-- Bio --}}
    <div style="padding:1rem; border-radius:0.75rem;
                background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
        <label style="display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase;
                      color:rgba(253,246,236,0.5); margin-bottom:0.5rem;">
            ✍️ Description de votre boutique
            <span style="font-weight:400; text-transform:none; font-size:0.65rem;">
                (max 500 car.)
            </span>
        </label>
        <textarea id="bio-input" rows="3" maxlength="500"
                  placeholder="Présentez votre boutique..."
                  style="width:100%; border-radius:0.5rem; padding:0.6rem 0.75rem;
                         font-size:0.8rem; resize:none; outline:none; box-sizing:border-box;
                         background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1);
                         color:#FDF6EC; font-family:inherit;">{{ $shopBio }}</textarea>
    </div>

    {{-- Bannière --}}
    <div style="padding:1rem; border-radius:0.75rem;
                background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);">
        <label style="display:block; font-size:0.7rem; font-weight:700; text-transform:uppercase;
                      color:rgba(253,246,236,0.5); margin-bottom:0.5rem;">
            🖼️ Bannière
            <span style="font-weight:400; text-transform:none; font-size:0.65rem;">JPG/PNG max 3Mo</span>
        </label>
        <form id="banner-form" method="POST" action="{{ route('vendor.customize.banner') }}"
              enctype="multipart/form-data">
            @csrf
            <input type="file" name="banner" accept="image/*"
                   onchange="document.getElementById('banner-form').submit()"
                   style="width:100%; font-size:0.75rem; color:rgba(253,246,236,0.6);">
        </form>
        @if($shopBanner)
            <div style="margin-top:0.5rem; border-radius:0.5rem; overflow:hidden; height:4rem;">
                <img src="{{ asset('storage/'.$shopBanner) }}"
                     style="width:100%; height:100%; object-fit:cover;">
            </div>
        @endif
    </div>

    {{-- Fonctionnalités premium --}}
    @if(!$isPremium)
        <div style="padding:1rem; border-radius:0.75rem; text-align:center;
                    background:rgba(0,0,0,0.3); border:1px dashed rgba(255,255,255,0.15);">
            <div style="font-size:1.5rem; margin-bottom:0.5rem;">🔒</div>
            <p style="font-size:0.8rem; color:rgba(253,246,236,0.5); margin-bottom:0.75rem;">
                Blocs personnalisés disponibles avec l'abonnement
                <strong style="color:var(--shop-color)">Plus Plus</strong>
            </p>
            <a href="{{ route('nav.help') }}"
               style="display:inline-block; padding:0.5rem 1.25rem; border-radius:0.5rem;
                      font-size:0.8rem; font-weight:700; text-decoration:none;
                      background:var(--shop-color); color:#2C1A0E;">
                Passer au Premium
            </a>
        </div>
    @endif

    {{-- Bouton Sauvegarder --}}
    <button onclick="saveCustomization()"
            style="width:100%; padding:0.75rem; border-radius:0.75rem; font-weight:900;
                   font-size:0.875rem; cursor:pointer; border:none;
                   background:var(--shop-color); color:#2C1A0E;">
        💾 Sauvegarder les modifications
    </button>

</div>

{{-- Overlay derrière le panneau --}}
<div id="edit-overlay"
     onclick="closePanel()"
     style="position:fixed; inset:0; z-index:499; background:rgba(0,0,0,0.5);
            display:none; backdrop-filter:blur(2px);">
</div>
@endif

<div class="py-12">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Fil d'Ariane --}}
        <nav style="display:flex; align-items:center; gap:0.5rem; font-size:0.75rem;
                    color:rgba(253,246,236,0.4); margin-bottom:2rem;">
            <a href="{{ route('products.index') }}"
               style="color:rgba(253,246,236,0.4); text-decoration:none;">
                🏪 Marketplace
            </a>
            <span>›</span>
            <span style="color:var(--shop-color)">
                {{ $seller->company_name ?? $seller->name }}
            </span>
        </nav>

        {{-- Profil vendeur --}}
        <div class="rounded-2xl p-6 mb-8 relative overflow-hidden"
             style="background:rgba(44,26,14,0.85); border:1px solid rgba(255,255,255,0.1);
                    box-shadow:0 8px 32px rgba(0,0,0,0.4)">

            {{-- Bannière --}}
            @if($shopBanner)
                <div style="position:absolute; inset:0; border-radius:1rem; overflow:hidden; opacity:0.25;">
                    <img src="{{ asset('storage/'.$shopBanner) }}"
                         style="width:100%; height:100%; object-fit:cover;">
                </div>
            @else
                <div style="position:absolute; inset:0;
                            background:radial-gradient(circle at 80% 50%, var(--shop-color) 0%, transparent 60%);
                            opacity:0.08; border-radius:1rem;">
                </div>
            @endif

            <div id="vendor-profile-header" class="relative">

                {{-- Avatar --}}
                <div class="rounded-2xl flex items-center justify-center text-3xl font-black"
                     style="width:6rem; height:6rem; flex-shrink:0;
                            background:rgba(255,255,255,0.08);
                            border:2px solid var(--shop-color); color:var(--shop-color)">
                    @if($seller->avatar)
                        <img src="{{ asset('storage/'.$seller->avatar) }}"
                             style="width:100%; height:100%; object-fit:cover; border-radius:1rem;"
                             alt="{{ $seller->name }}">
                    @else
                        {{ strtoupper(substr($seller->company_name ?? $seller->name, 0, 1)) }}
                    @endif
                </div>

                {{-- Infos --}}
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; flex-wrap:wrap; align-items:center; gap:0.75rem; margin-bottom:0.25rem;">
                        <h1 class="text-2xl font-black"
                            style="font-family:'Playfair Display',serif; color:#FDF6EC">
                            {{ $seller->company_name ?? $seller->name }}
                        </h1>
                        <span class="text-xs font-bold px-3 py-1 rounded-full"
                              style="background:{{ $seller->role === 'vendeur_pro' ? 'rgba(74,222,128,0.9)' : 'rgba(96,165,250,0.9)' }};
                                     color:#2C1A0E">
                            {{ $seller->role === 'vendeur_pro' ? '🏢 Entreprise Pro' : '👤 Vendeur Particulier' }}
                        </span>
                    </div>

                    @if($seller->company_name)
                        <p class="text-sm" style="color:rgba(253,246,236,0.5); margin-bottom:0.5rem;">
                            Gérant : {{ $seller->name }}
                        </p>
                    @endif

                    {{-- Stats --}}
                    <div style="display:flex; flex-wrap:wrap; gap:1.5rem; margin-top:0.5rem;">
                        <div>
                            <span class="text-xl font-black" style="color:var(--shop-color)">{{ $totalProducts }}</span>
                            <span class="text-xs" style="color:rgba(253,246,236,0.4); margin-left:0.25rem;">produits</span>
                        </div>
                        <div>
                            <span class="text-xl font-black" style="color:var(--shop-color)">{{ $sellerCategories->count() }}</span>
                            <span class="text-xs" style="color:rgba(253,246,236,0.4); margin-left:0.25rem;">catégories</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.4rem;">
                            <span style="width:0.5rem; height:0.5rem; border-radius:50%; background:#4ade80; display:inline-block;"></span>
                            <span class="text-xs" style="color:#4ade80">Boutique active</span>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <p id="bio-display"
                       style="font-size:0.875rem; color:rgba(253,246,236,0.65);
                              margin-top:0.75rem; line-height:1.6;
                              {{ empty($shopBio) ? 'display:none' : '' }}">
                        {{ $shopBio }}
                    </p>
                </div>

                {{-- Bouton contacter (acheteurs seulement) --}}
                @auth
                    @if(auth()->user()->isAcheteur())
                        <form action="{{ route('messages.start-vendor', $seller) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    style="padding:0.6rem 1.25rem; border-radius:0.75rem; font-weight:700;
                                           font-size:0.8rem; cursor:pointer; white-space:nowrap; border:none;
                                           background:rgba(255,255,255,0.08);
                                           border:1px solid var(--shop-color);
                                           color:var(--shop-color);">
                                💬 Contacter le vendeur
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Layout principal --}}
        <div id="vendor-shop-layout">

            {{-- Sidebar --}}
            <div id="vendor-shop-sidebar">
                <form method="GET" action="{{ route('shop.vendor', $seller->id) }}"
                      style="display:flex; flex-direction:column; gap:1rem;">

                    <div style="padding:1.25rem; border-radius:1rem;
                                background:rgba(44,26,14,0.85); border:1px solid rgba(255,255,255,0.08)">
                        <h3 style="font-size:0.7rem; font-weight:700; text-transform:uppercase;
                                   color:var(--shop-color); opacity:0.8; margin-bottom:0.75rem;">
                            Recherche
                        </h3>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Dans cette boutique..."
                               style="width:100%; border-radius:0.75rem; padding:0.5rem 0.75rem; font-size:0.875rem;
                                      background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1);
                                      color:#FDF6EC; outline:none; box-sizing:border-box;">
                    </div>

                    <div style="padding:1.25rem; border-radius:1rem;
                                background:rgba(44,26,14,0.85); border:1px solid rgba(255,255,255,0.08)">
                        <h3 style="font-size:0.7rem; font-weight:700; text-transform:uppercase;
                                   color:var(--shop-color); opacity:0.8; margin-bottom:0.75rem;">
                            Catégories
                        </h3>
                        <div style="display:flex; flex-direction:column; gap:0.25rem;">
                            <a href="{{ route('shop.vendor', $seller->id) }}"
                               style="display:flex; align-items:center; justify-content:space-between;
                                      font-size:0.875rem; padding:0.5rem 0.75rem; border-radius:0.5rem;
                                      text-decoration:none;
                                      color:{{ !request('category') ? 'var(--shop-color)' : 'rgba(253,246,236,0.6)' }};
                                      background:{{ !request('category') ? 'rgba(255,255,255,0.08)' : 'transparent' }};">
                                <span>Tous les produits</span>
                                <span style="font-size:0.7rem; padding:0.1rem 0.4rem; border-radius:9999px;
                                             background:rgba(255,255,255,0.08); color:var(--shop-color);">
                                    {{ $totalProducts }}
                                </span>
                            </a>
                            @foreach($sellerCategories as $cat)
                                <a href="{{ route('shop.vendor', ['vendor' => $seller->id, 'category' => $cat->id]) }}"
                                   style="display:flex; align-items:center; justify-content:space-between;
                                          font-size:0.875rem; padding:0.5rem 0.75rem; border-radius:0.5rem;
                                          text-decoration:none;
                                          color:{{ request('category') == $cat->id ? 'var(--shop-color)' : 'rgba(253,246,236,0.6)' }};
                                          background:{{ request('category') == $cat->id ? 'rgba(255,255,255,0.08)' : 'transparent' }};">
                                    <span>{{ $cat->name }}</span>
                                    <span style="font-size:0.7rem; padding:0.1rem 0.4rem; border-radius:9999px;
                                                 background:rgba(255,255,255,0.06); color:rgba(253,246,236,0.5);">
                                        {{ $cat->products_count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div style="padding:1.25rem; border-radius:1rem;
                                background:rgba(44,26,14,0.85); border:1px solid rgba(255,255,255,0.08)">
                        <h3 style="font-size:0.7rem; font-weight:700; text-transform:uppercase;
                                   color:var(--shop-color); opacity:0.8; margin-bottom:0.75rem;">
                            Trier par
                        </h3>
                        <select name="sort"
                                style="width:100%; border-radius:0.75rem; padding:0.5rem 0.75rem; font-size:0.875rem;
                                       background:rgba(44,26,14,0.95); border:1px solid rgba(255,255,255,0.1);
                                       color:#FDF6EC; outline:none;">
                            <option value="newest"    {{ request('sort') == 'newest'    ? 'selected' : '' }}>Plus récents</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc"{{ request('sort') == 'price_desc'? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </div>

                    <button type="submit"
                            style="width:100%; padding:0.75rem; border-radius:0.75rem; font-weight:700;
                                   font-size:0.875rem; border:none; cursor:pointer;
                                   background:var(--shop-color); color:#2C1A0E;">
                        🔍 Filtrer
                    </button>

                    @if(request('search') || request('category') || request('sort'))
                        <a href="{{ route('shop.vendor', $seller->id) }}"
                           style="display:block; text-align:center; font-size:0.875rem;
                                  color:rgba(253,246,236,0.4); text-decoration:none;">
                            ✕ Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Grille produits --}}
            <div id="vendor-shop-content">

                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                    <h2 class="font-bold text-lg" style="color:#FDF6EC">
                        @if(request('category'))
                            {{ $sellerCategories->firstWhere('id', request('category'))?->name ?? 'Produits' }}
                        @else
                            Tous les produits
                        @endif
                    </h2>
                    <span style="font-size:0.875rem; color:rgba(253,246,236,0.4)">
                        {{ $products->total() }} résultat(s)
                    </span>
                </div>

                @if($products->isEmpty())
                    <div style="text-align:center; padding:5rem 1rem; border-radius:1rem;
                                background:rgba(44,26,14,0.5); border:2px dashed rgba(255,255,255,0.1)">
                        <div style="font-size:3.75rem; margin-bottom:1rem;">🔍</div>
                        <p style="color:rgba(253,246,236,0.5)">Aucun produit trouvé.</p>
                    </div>
                @else
                    {{-- Message d'aide drag & drop pour le propriétaire --}}
                    @if($isOwner)
                        <div style="padding:0.6rem 1rem; border-radius:0.5rem; margin-bottom:1rem;
                                    background:rgba(var(--shop-color), 0.1);
                                    border:1px solid rgba(255,255,255,0.1);
                                    font-size:0.75rem; color:rgba(253,246,236,0.5); text-align:center;">
                            ↕️ Glissez-déposez les produits pour changer leur ordre — l'ordre est sauvegardé automatiquement
                        </div>
                    @endif

                    <div id="vendor-shop-products-grid">
                        @foreach($products as $product)
                            <div class="product-card-wrapper {{ $isOwner ? 'sortable-item' : '' }}"
                                 data-product-id="{{ $product->id }}"
                                 style="{{ $isOwner ? 'cursor:grab;' : '' }}">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="gasy-card block"
                                   style="overflow:hidden; text-decoration:none;
                                          {{ $isOwner ? 'pointer-events:none;' : '' }}">
                                    <div style="position:relative; overflow:hidden; height:12rem;">
                                        <img src="{{ asset('storage/'.$product->image) }}"
                                             alt="{{ $product->name }}"
                                             style="width:100%; height:100%; object-fit:cover; transition:transform 0.5s;">
                                        <span style="position:absolute; top:0.75rem; left:0.75rem;
                                                     font-size:0.75rem; font-weight:700;
                                                     padding:0.25rem 0.5rem; border-radius:9999px;
                                                     background:var(--shop-color); color:#2C1A0E;">
                                            {{ $product->category->name }}
                                        </span>
                                        @if($isOwner)
                                            <div style="position:absolute; top:0.75rem; right:0.75rem;
                                                        background:rgba(0,0,0,0.6); border-radius:0.4rem;
                                                        padding:0.3rem 0.5rem; font-size:0.75rem; color:#FDF6EC;
                                                        cursor:grab;">
                                                ⠿
                                            </div>
                                        @endif
                                    </div>
                                    <div style="padding:1rem;">
                                        <h3 style="font-weight:700; color:#FDF6EC;">{{ $product->name }}</h3>
                                        <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.75rem;">
                                            <span style="font-size:1.125rem; font-weight:900; color:var(--shop-color);">
                                                {{ number_format($product->price, 0, ',', ' ') }} Ar
                                            </span>
                                            <span style="font-size:0.75rem; padding:0.25rem 0.5rem; border-radius:9999px;
                                                         background:rgba(74,222,128,0.15); color:#4ade80;">
                                                Stock: {{ $product->stock }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                @if($isOwner)
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       style="display:block; text-align:center; padding:0.4rem;
                                              font-size:0.75rem; color:rgba(253,246,236,0.4);
                                              text-decoration:none; border-top:1px solid rgba(255,255,255,0.06);">
                                        Voir la fiche →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top:2rem;">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif

                {{-- Blocs premium --}}
                @if(!empty($shopBlocks))
                    <div style="display:flex; flex-direction:column; gap:1rem; margin-top:2rem;">
                        @foreach($shopBlocks as $block)
                            @if($block['type'] === 'text' && !empty($block['content']))
                                <div style="padding:1.25rem; border-radius:1rem;
                                            background:rgba(44,26,14,0.7); border:1px solid rgba(255,255,255,0.08);
                                            color:rgba(253,246,236,0.8); line-height:1.7;">
                                    {!! nl2br(e($block['content'])) !!}
                                </div>
                            @elseif($block['type'] === 'image' && !empty($block['path']))
                                <div style="border-radius:1rem; overflow:hidden;">
                                    <img src="{{ asset('storage/'.$block['path']) }}"
                                         style="width:100%; max-height:20rem; object-fit:cover; display:block;">
                                </div>
                            @elseif($block['type'] === 'banner' && !empty($block['text']))
                                <div style="padding:1rem 1.5rem; border-radius:1rem; text-align:center;
                                            font-weight:900; color:#2C1A0E;
                                            background:{{ $block['bg'] ?? '#F4A429' }}">
                                    {{ $block['text'] }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    #vendor-profile-header { display:flex; flex-direction:column; align-items:flex-start; gap:1.5rem; }
    #vendor-shop-layout    { display:flex; flex-direction:column; gap:2rem; }
    #vendor-shop-sidebar   { width:100%; flex-shrink:0; }
    #vendor-shop-content   { flex:1; min-width:0; }
    #vendor-shop-products-grid {
        display:grid; grid-template-columns:1fr; gap:1.5rem;
    }
    @media (min-width:640px) {
        #vendor-profile-header { flex-direction:row; align-items:center; }
        #vendor-shop-products-grid { grid-template-columns:repeat(2,1fr); }
    }
    @media (min-width:1024px) {
        #vendor-shop-layout  { flex-direction:row; }
        #vendor-shop-sidebar { width:260px; }
        #vendor-shop-products-grid { grid-template-columns:repeat(3,1fr); }
    }
    .sortable-ghost { opacity:0.4; transform:scale(0.98); }
    .sortable-drag  { cursor:grabbing !important; box-shadow:0 20px 40px rgba(0,0,0,0.5) !important; }
    .gasy-card:hover img { transform:scale(1.05); }
</style>

@if($isOwner)
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
    const CSRF   = document.querySelector('meta[name=csrf-token]').content;
    const SAVE_URL = '{{ route("vendor.customize.product-order") }}';
    const BASE_URL = '{{ route("vendor.customize.base") }}';

    // ── Drag & Drop ──────────────────────────────────────────────
    const grid = document.getElementById('vendor-shop-products-grid');
    if (grid) {
        Sortable.create(grid, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            dragClass:  'sortable-drag',
            onEnd: function () {
                const order = Array.from(grid.querySelectorAll('[data-product-id]'))
                    .map(el => parseInt(el.dataset.productId));
                saveOrder(order);
            }
        });
    }

    function saveOrder(order) {
        fetch(SAVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ order: JSON.stringify(order) })
        })
        .then(r => r.json())
        .then(() => showStatus('✅ Ordre sauvegardé'))
        .catch(() => showStatus('❌ Erreur de sauvegarde'));
    }

    // ── Panneau de personnalisation ───────────────────────────────
    function openPanel()  {
        document.getElementById('edit-panel').style.right   = '0';
        document.getElementById('edit-overlay').style.display = 'block';
    }
    function closePanel() {
        document.getElementById('edit-panel').style.right   = '-360px';
        document.getElementById('edit-overlay').style.display = 'none';
    }

    // ── Couleur en temps réel ─────────────────────────────────────
    let colorTimeout;
    function onColorChange(val) {
        // Mise à jour visuelle instantanée
        document.getElementById('shop-color-style').textContent =
            `:root { --shop-color: ${val}; } .shop-primary { color:${val} !important; }`;
        document.getElementById('color-hex-display').textContent = val;
        // Auto-save après 800ms d'inactivité
        clearTimeout(colorTimeout);
        colorTimeout = setTimeout(() => saveCustomization(), 800);
    }

    // ── Sauvegarde bio + couleur ──────────────────────────────────
    function saveCustomization() {
        const color = document.getElementById('color-picker').value;
        const bio   = document.getElementById('bio-input').value;

        // Mise à jour affichage bio en temps réel
        const bioDisplay = document.getElementById('bio-display');
        if (bio.trim()) {
            bioDisplay.textContent  = bio;
            bioDisplay.style.display = '';
        } else {
            bioDisplay.style.display = 'none';
        }

        fetch(BASE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ primary_color: color, bio: bio })
        })
        .then(r => r.json())
        .then(() => showStatus('✅ Modifications sauvegardées'))
        .catch(() => showStatus('❌ Erreur'));
    }

    // ── Notification de statut ────────────────────────────────────
    let statusTimeout;
    function showStatus(msg) {
        const el = document.getElementById('save-status');
        el.textContent  = msg;
        el.style.display = 'inline';
        clearTimeout(statusTimeout);
        statusTimeout = setTimeout(() => { el.style.display = 'none'; }, 3000);
    }
</script>
@endif

</x-app-layout>