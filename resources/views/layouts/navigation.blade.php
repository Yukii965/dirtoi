<nav class="gasy-nav sticky top-0 px-6 py-3" style="z-index: 9999;">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
                <span class="text-white font-black text-lg">G</span>
            </div>
            <span class="font-black text-xl hidden lg:block" style="font-family: 'Playfair Display', serif;">
                <span style="color: #F4A429">Gasy</span><span class="text-white">Market</span>
            </span>
        </a>

        {{-- Barre de recherche --}}
        <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex flex-1 max-w-xl mx-6 ml-16">
            <div class="flex w-full">
                <input type="text" name="search"
                    placeholder="🔍 Rechercher un produit..."
                    class="flex-1 rounded-l-xl px-4 py-2 text-sm focus:outline-none"
                    style="background: rgba(255,255,255,0.12); border: 1px solid rgba(244,164,41,0.3); color: #FDF6EC; placeholder-color: rgba(253,246,236,0.5)">
                <button type="submit"
                    class="px-4 py-2 rounded-r-xl font-bold text-sm"
                    style="background: #F4A429; color: #2C1A0E">
                    🔍
                </button>
            </div>
        </form>

        {{-- Menu droite --}}
        <div class="flex items-center gap-3">

            @auth
                {{-- Dropdown utilisateur --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                    class="w-8 h-8 rounded-full object-cover"
                                    style="border: 2px solid #F4A429">
                            @else
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                    style="background: #F4A429; color: #2C1A0E">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="hidden md:block text-white text-sm font-medium">
                                {{ Auth::user()->name }}
                            </span>
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        {{-- Lien profil --}}
                        <x-dropdown-link :href="route('profile.edit')">
                            👤 Mon profil
                        </x-dropdown-link>

                        {{-- Lien dashboard --}}
                        <x-dropdown-link :href="route('dashboard')">
                            🏠 Mon tableau de bord
                        </x-dropdown-link>

                        {{-- Déconnexion --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 Se déconnecter
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                {{-- Panier : visible seulement pour les acheteurs --}}
                @if(auth()->user()->isAcheteur())
                    <a href="{{ route('cart.index') }}"
                    class="relative p-2 rounded-xl hover:bg-white/10 transition">
                        🛒
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center"
                                style="background: #F4A429; color: #2C1A0E">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                @endif

                {{-- Liens rapides --}}
                <div class="hidden md:flex items-center gap-4 mx-4">
                    <a href="{{ route('products.index') }}"
                        class="text-sm hover:text-yellow-400 transition"
                        style="color: rgba(253,246,236,0.6)">
                            Boutique
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-4 ml-4">
                    <a href="{{ route('nav.help') }}"
                    class="text-sm hover:text-yellow-400 transition"
                    style="color: rgba(253,246,236,0.6)">
                        Aide
                    </a>
                </div>

                {{-- Bouton vendre (vendeurs seulement) --}}
                @if(Auth::user()->isVendeur() || Auth::user()->isAdmin())
                    <a href="{{ route('nav.sell') }}"
                       class="hidden md:block btn-gasy text-sm px-4 py-2 rounded-xl">
                        + Vendre
                    </a>
                @endif

            @else
                {{-- Visiteur non connecté --}}
                <a href="{{ route('login') }}"
                   class="text-yellow-400 hover:text-yellow-300 text-sm font-medium transition">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                   class="btn-gasy text-sm px-4 py-2 rounded-xl">
                    S'inscrire
                </a>
            @endauth

        </div>
    </div>
</nav>