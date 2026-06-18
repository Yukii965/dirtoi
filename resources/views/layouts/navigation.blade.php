{{-- Calcul des messages non lus — défini ici pour être disponible
     partout dans la navigation (menu desktop ET mobile).
     Vaut 0 si non connecté ou si la table messages n'existe pas encore. --}}
@php
    $unreadTotal = 0;
    if (auth()->check()) {
        try {
            $unreadTotal = \App\Models\Message::whereHas('conversation', function($q) {
                $q->where('buyer_id', auth()->id())
                  ->orWhere('vendor_id', auth()->id());
            })->where('sender_id', '!=', auth()->id())
              ->whereNull('read_at')
              ->count();
        } catch (\Exception $e) {
            // Silencieux si la table messages n'existe pas encore
            $unreadTotal = 0;
        }
    }
@endphp
<nav style="background: rgba(44,26,14,0.97); border-bottom: 1px solid rgba(244,164,41,0.2); position: sticky; top: 0; z-index: 9999; backdrop-filter: blur(10px);">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0.75rem 1.25rem;">

        {{-- Desktop + Mobile top bar --}}
        <div style="display: flex; align-items: center; gap: 1rem; width: 100%;">

            {{-- Logo --}}
            <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0; text-decoration: none;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #F4A429, #E07B2A); display: flex; align-items: center; justify-content: center;">
                    <span style="color: white; font-weight: 900; font-size: 1.1rem;">G</span>
                </div>
                <span style="font-family: 'Playfair Display', serif; font-weight: 900; font-size: 1.2rem;">
                    <span style="color: #F4A429">Gasy</span><span style="color: white">Market</span>
                </span>
            </a>

            {{-- Wrapper flex qui grandit --}}
            <div id="search-wrapper" style="display: none; flex: 1; min-width: 0;">
                <form action="{{ auth()->check() ? route('products.index') : route('login') }}" method="GET"
                    style="display: flex; width: 100%;">
                    <input type="text" name="search" placeholder="🔍 Rechercher un produit..."
                        style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(244,164,41,0.3); border-right: none; border-radius: 0.75rem 0 0 0.75rem; padding: 0.5rem 1rem; color: #FDF6EC; font-size: 0.875rem; outline: none;">
                    <button type="submit"
                            style="background: #F4A429; color: #2C1A0E; border: none; border-radius: 0 0.75rem 0.75rem 0; padding: 0.5rem 1rem; cursor: pointer; font-weight: 700; white-space: nowrap;">
                        🔍
                    </button>
                </form>
            </div>
            {{-- Actions droite --}}
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;">

                @auth
                    {{-- Avatar dropdown --}}
                    <div style="position: relative;" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                style="display: flex; align-items: center; gap: 0.5rem; background: none; border: none; cursor: pointer; padding: 0.25rem;">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                     style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid #F4A429;">
                            @else
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #F4A429; color: #2C1A0E; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span style="color: white; font-size: 0.875rem; display: none;" id="username-text">
                                {{ Auth::user()->name }}
                            </span>
                        </button>

                        {{-- Dropdown menu --}}
                        <div x-show="open"
                             x-transition
                             style="position: absolute; right: 0; top: 110%; min-width: 200px; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5);"
                             class="dropdown-menu">
                            <a href="{{ route('dashboard') }}"
                               style="display: block; padding: 0.75rem 1rem; font-size: 0.875rem; text-decoration: none; transition: all 0.15s;"
                               onmouseover="this.style.background='rgba(244,164,41,0.15)'; this.style.color='#F4A429'"
                               onmouseout="this.style.background='transparent'; this.style.color='rgba(253,246,236,0.8)'"
                               style="color: rgba(253,246,236,0.8)">
                                🏠 Mon tableau de bord
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               style="display: block; padding: 0.75rem 1rem; font-size: 0.875rem; text-decoration: none; color: rgba(253,246,236,0.8); transition: all 0.15s;"
                               onmouseover="this.style.background='rgba(244,164,41,0.15)'; this.style.color='#F4A429'"
                               onmouseout="this.style.background='transparent'; this.style.color='rgba(253,246,236,0.8)'">
                                👤 Mon profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        style="display: block; width: 100%; text-align: left; padding: 0.75rem 1rem; font-size: 0.875rem; background: none; border: none; cursor: pointer; color: rgba(253,246,236,0.8); transition: all 0.15s; border-top: 1px solid rgba(244,164,41,0.1);"
                                        onmouseover="this.style.background='rgba(239,68,68,0.15)'; this.style.color='#f87171'"
                                        onmouseout="this.style.background='transparent'; this.style.color='rgba(253,246,236,0.8)'">
                                    🚪 Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Icône messagerie avec badge non-lus --}}
                    @php
                        $unreadTotal = \App\Models\Message::whereHas('conversation', function($q) {
                            $q->where('buyer_id', auth()->id())->orWhere('vendor_id', auth()->id());
                        })->where('sender_id', '!=', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <a href="{{ route('messages.index') }}"
                    style="position:relative; display:inline-flex; align-items:center;
                            color:rgba(253,246,236,0.7); text-decoration:none; margin-right:0.5rem;">
                        💬
                        @if($unreadTotal > 0)
                            <span style="position:absolute; top:-6px; right:-8px;
                                        background:#F4A429; color:#2C1A0E; font-size:0.6rem;
                                        font-weight:900; padding:0.1rem 0.35rem;
                                        border-radius:9999px; line-height:1.2;">
                                {{ $unreadTotal > 9 ? '9+' : $unreadTotal }}
                            </span>
                        @endif
                    </a>

                    {{-- Panier (acheteur seulement) --}}
                    @if(auth()->user()->isAcheteur())
                        <a href="{{ route('cart.index') }}"
                           style="position: relative; color: white; text-decoration: none; font-size: 1.25rem; padding: 0.25rem;">
                            🛒
                            @if(session('cart') && count(session('cart')) > 0)
                                <span style="position: absolute; top: -4px; right: -4px; background: #F4A429; color: #2C1A0E; border-radius: 50%; width: 18px; height: 18px; font-size: 0.65rem; font-weight: 900; display: flex; align-items: center; justify-content: center;">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>
                    @endif

                    {{-- Liens Boutique et Aide --}}
                    @if(auth()->user()->isVendeur())
                        <a href="{{ route('vendor.customize') }}"
                            style="color: rgba(253,246,236,0.7); text-decoration:none; font-size:0.9rem;">
                            🏪 Ma boutique
                        </a>
                    @else
                        <a href="{{ route('products.index') }}"
                            style="color: rgba(253,246,236,0.7); text-decoration:none; font-size:0.9rem;">
                            Boutique
                        </a>
                    @endif
                    <a href="{{ route('nav.help') }}"
                        style="color: rgba(253,246,236,0.6); font-size: 0.875rem; text-decoration: none;"
                        onmouseover="this.style.color='#F4A429'"
                        onmouseout="this.style.color='rgba(253,246,236,0.6)'">
                            Aide
                    </a>

                    {{-- Bouton Vendre (vendeurs seulement) --}}
                    @if(Auth::user()->isVendeur())
                        <a href="{{ route('nav.sell') }}"
                           style="background: #F4A429; color: #2C1A0E; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.65rem; text-decoration: none; font-size: 0.8rem; white-space: nowrap;">
                            + Vendre
                        </a>
                    @endif

                @else
                    <a href="{{ route('login') }}"
                       style="color: #F4A429; font-weight: 600; font-size: 0.875rem; text-decoration: none;">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}"
                       style="background: #F4A429; color: #2C1A0E; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.65rem; text-decoration: none; font-size: 0.8rem; white-space: nowrap;">
                        S'inscrire
                    </a>
                @endauth

                {{-- Bouton hamburger mobile --}}
                <button id="menu-toggle"
                        style="background: none; border: 1px solid rgba(244,164,41,0.3); border-radius: 0.5rem; padding: 0.4rem 0.6rem; cursor: pointer; color: #F4A429; font-size: 1.1rem; display: none;">
                    ☰
                </button>
            </div>
        </div>

        {{-- Menu mobile (caché par défaut) --}}
        <div id="mobile-menu" style="display: none; padding-top: 1rem; border-top: 1px solid rgba(244,164,41,0.15); margin-top: 0.75rem;">

            {{-- Barre de recherche : visible pour tout le monde
                (redirige vers login si non connecté, comme le menu desktop) --}}
            <form action="{{ auth()->check() ? route('products.index') : route('login') }}" method="GET"
                style="margin-bottom: {{ auth()->check() ? '1rem' : '0' }};">
                <div style="display: flex;">
                    <input type="text" name="search" placeholder="🔍 Rechercher..."
                        style="flex: 1; background: rgba(255,255,255,0.1); border: 1px solid rgba(244,164,41,0.3); border-right: none; border-radius: 0.75rem 0 0 0.75rem; padding: 0.6rem 1rem; color: #FDF6EC; font-size: 0.875rem; outline: none;">
                    <button type="submit"
                            style="background: #F4A429; color: #2C1A0E; border: none; border-radius: 0 0.75rem 0.75rem 0; padding: 0.6rem 1rem; cursor: pointer;">
                        🔍
                    </button>
                </div>
            </form>

            <a href="{{ route('messages.index') }}"
                style="color: rgba(253,246,236,0.7); text-decoration: none; padding: 0.6rem 0;
                    font-size: 0.9rem; border-bottom: 1px solid rgba(244,164,41,0.1);">
                💬 Messages
                @if($unreadTotal > 0)
                    <span style="background:#F4A429; color:#2C1A0E; font-size:0.65rem;
                                font-weight:900; padding:0.1rem 0.4rem; border-radius:9999px; margin-left:0.4rem;">
                        {{ $unreadTotal }}
                    </span>
                @endif
            </a>
            
            {{-- Liens Boutique / Aide : uniquement pour les utilisateurs connectés,
                exactement comme dans le menu desktop --}}
            @auth
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @if(auth()->user()->isVendeur())
                        <a href="{{ route('vendor.customize') }}"
                            style="color: rgba(253,246,236,0.7); text-decoration: none; padding: 0.6rem 0;
                                font-size: 0.9rem; border-bottom: 1px solid rgba(244,164,41,0.1);">
                            🏪 Ma boutique
                        </a>
                    @else
                        <a href="{{ route('products.index') }}"
                        style="color: rgba(253,246,236,0.7); text-decoration: none; padding: 0.6rem 0;
                                font-size: 0.9rem; border-bottom: 1px solid rgba(244,164,41,0.1);">
                            🛍️ Boutique
                        </a>
                    @endif
                    <a href="{{ route('nav.help') }}"
                    style="color: rgba(253,246,236,0.7); text-decoration: none; padding: 0.6rem 0; font-size: 0.9rem;">
                        ❓ Aide
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Dropdown style --}}
<style>
    .dropdown-menu {
        background: rgba(44,26,14,0.98);
        border: 1px solid rgba(244,164,41,0.2);
    }
    @media (min-width: 768px) {
        #search-wrapper { display: flex !important; }
        #username-text { display: block !important; }
        #menu-toggle { display: none !important; }
    }
    @media (max-width: 767px) {
        #menu-toggle { display: block !important; }
    }
</style>

<script>
    // Hamburger menu toggle
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    });
</script>