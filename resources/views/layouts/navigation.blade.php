<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="relative w-11 h-11 flex items-center justify-center">
                            <div class="absolute inset-0 bg-blue-600/20 blur-xl rounded-full group-hover:bg-blue-600/40 transition-all"></div>
                            
                            <svg viewBox="0 0 100 100" class="relative w-full h-full text-blue-500 drop-shadow-[0_0_10px_rgba(59,130,246,0.6)] group-hover:rotate-[-10deg] transition-all duration-300">
                                <path d="M25,20 C25,10 75,10 75,20" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round"/>
                                <path d="M15,30 L85,30 L75,80 L25,80 Z" fill="currentColor" fill-opacity="0.1" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/>
                                <line x1="35" y1="30" x2="40" y2="80" stroke="currentColor" stroke-width="3"/>
                                <line x1="65" y1="30" x2="60" y2="80" stroke="currentColor" stroke-width="3"/>
                                <circle cx="50" cy="55" r="8" fill="currentColor" class="animate-pulse shadow-[0_0_15px_currentColor]"/>
                            </svg>
                        </div>
                        
                        <span class="text-3xl font-black tracking-tighter uppercase italic text-blue-500">
                            Dir<span class="text-white group-hover:text-blue-400 transition-colors">Toi</span>
                        </span>
                    </a>
                </div>
                
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-white hover:text-yellow-400">
                        {{ __('Boutique') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden md:flex flex-1 mx-10">
                <input type="text" class="w-full rounded-l-md border-none text-gray-900 focus:ring-yellow-500" placeholder="Rechercher un produit...">
                <button class="bg-yellow-500 px-4 rounded-r-md hover:bg-yellow-600">
                    <svg class="h-5 w-5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
            <div class="flex items-center space-x-6">
            <div class="relative">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-400 hover:text-cyan-400 transition duration-150 ease-in-out">
                                <div class="bg-gray-800 p-2 rounded-full border border-gray-700 shadow-[0_0_10px_rgba(6,182,212,0.2)]">
                                    <svg class="h-6 w-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="ml-2 hidden md:block text-white font-bold">{{ Auth::user()->name }}</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-cyan-500/10">
                                {{ __('Mon Terminal') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Déconnexion') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="flex items-center group">
                        <div class="bg-gray-800 p-2 rounded-full border border-gray-700 group-hover:border-cyan-500 group-hover:shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all">
                            <svg class="h-6 w-6 text-gray-400 group-hover:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="ml-2 hidden md:block text-gray-400 group-hover:text-white font-medium text-sm transition-colors uppercase tracking-widest">Login</span>
                    </a>
                @endauth
            </div>

            <a href="{{ route('cart.index') }}" class="relative group">
                <div class="bg-gray-800 p-2 rounded-full border border-gray-700 group-hover:border-yellow-500 transition-all">
                    <svg class="h-6 w-6 text-gray-400 group-hover:text-yellow-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-1 -right-1 bg-cyan-500 text-gray-950 font-black text-[10px] rounded-full h-5 w-5 flex items-center justify-center border-2 border-gray-950 shadow-[0_0_10px_rgba(6,182,212,0.5)]">
                        @php $count = 0; foreach(session('cart') as $item) { $count += $item['quantity']; } @endphp
                        {{ $count }}
                    </span>
                @endif
            </a>
        </div>
        </div>
    </div>
</nav>

<div class="bg-gray-800 text-white text-sm py-2 shadow-inner" style="overflow: visible !important;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center space-x-6" style="overflow: visible !important;">
        
        <div x-data="{ open: false }" class="relative flex-shrink-0" @click.away="open = false">
            <button @click="open = !open" 
                    class="flex items-center bg-gray-700 hover:bg-gray-600 px-3 py-1.5 rounded-md font-bold transition-all duration-200 focus:outline-none group">
                <svg class="h-5 w-5 mr-2 text-yellow-500 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <span>Toutes</span>
            </button>

            <div x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-1"
                class="absolute left-0 top-full mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100"
                style="z-index: 9999; position: absolute;">
                
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Parcourir par univers</h3>
                </div>

                <div class="py-2 max-h-80 overflow-y-auto">
                    @php $allCats = \App\Models\Category::all(); @endphp
                    @foreach($allCats as $category)
                        <a href="{{ route('home', ['category' => $category->slug]) }}" 
                        class="group flex items-center justify-between px-4 py-3 hover:bg-yellow-50 transition-colors">
                            <span class="text-gray-700 group-hover:text-yellow-700 font-medium text-sm">{{ $category->name }}</span>
                            <svg class="h-4 w-4 flex-shrink-0 text-gray-300 group-hover:text-yellow-500 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>

                <div class="bg-yellow-50 px-4 py-3 border-t border-yellow-100">
                    <a href="{{ route('home') }}" class="text-sm font-bold text-yellow-700 hover:text-yellow-800 flex items-center">
                        Voir tous les produits
                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Liens navbar --}}
        <a href="{{ route('nav.bestsellers') }}" class="hover:border-white border border-transparent px-2 py-1 transition">Meilleures Ventes</a>
        <a href="{{ route('nav.deals') }}" class="hover:border-white border border-transparent px-2 py-1 transition">Offres du jour</a>
        <a href="{{ route('nav.help') }}" class="hover:border-white border border-transparent px-2 py-1 transition">Service Client</a>
        <a href="{{ route('nav.wishlist') }}" class="hover:border-white border border-transparent px-2 py-1 transition">Listes d'envies</a>
        <a href="{{ route('nav.sell') }}" class="bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-3 py-1 rounded-md hover:bg-yellow-500 hover:text-gray-900 transition font-bold">Vendre sur DirToi</a>

        <div class="ml-auto hidden md:flex items-center text-xs flex-shrink-0">
            <svg class="h-4 w-4 mr-1 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            </svg>
            <span>Livrer à : <strong>Madagascar</strong></span>
        </div>
    </div>
</div>