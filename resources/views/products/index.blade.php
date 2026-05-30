<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Hero Banner --}}
            <div class="relative rounded-3xl overflow-hidden mb-16 h-80 flex items-center shadow-2xl"
                style="border: 1px solid rgba(244,164,41,0.3); z-index: 1;">

                {{-- Image background --}}
                <img src="/images/baobab-sunset.jpg"
                     class="absolute inset-0 w-full h-full object-cover opacity-40">

                {{-- Overlay gradient --}}
                <div class="absolute inset-0"
                     style="background: linear-gradient(to right, rgba(44,26,14,0.95), rgba(44,26,14,0.6), transparent)">
                </div>

                {{-- Contenu hero --}}
                <div class="relative z-10 px-12">
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full mb-4"
                          style="background: #F4A429; color: #2C1A0E">
                        🇲🇬 MARKETPLACE MALGACHE
                    </span>
                    <h1 class="text-6xl font-extrabold leading-tight"
                        style="font-family: 'Playfair Display', serif; color: #FDF6EC">
                        <span style="color: #F4A429">Gasy</span>Market
                    </h1>
                    <p class="mt-3 text-xl max-w-xl" style="color: rgba(253,246,236,0.8)">
                        Achetez et vendez des produits locaux — vite, simple et sécurisé 🌅
                    </p>
                    @guest
                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('register') }}" class="btn-gasy px-6 py-3 rounded-xl font-bold">
                                Commencer gratuitement
                            </a>
                            <a href="{{ route('login') }}"
                               class="px-6 py-3 rounded-xl font-bold transition"
                               style="border: 1px solid rgba(244,164,41,0.4); color: #F4A429">
                                Se connecter
                            </a>
                        </div>
                    @endguest
                </div>
            </div>

            {{-- Titre section produits --}}
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold" style="color: #F4A429; font-family: 'Playfair Display', serif">
                    Produits disponibles
                </h2>
                <span class="text-sm" style="color: rgba(253,246,236,0.5)">
                    {{ $products->total() }} produits
                </span>
            </div>

            {{-- Grille de produits --}}
            @if($products->isEmpty())
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🛍️</div>
                    <p style="color: rgba(253,246,236,0.5)">Aucun produit disponible pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="gasy-card group overflow-hidden block">

                        {{-- Image produit --}}
                        <div class="overflow-hidden h-52 relative">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 alt="{{ $product->name }}">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                                 style="background: linear-gradient(to top, rgba(44,26,14,0.8), transparent)">
                            </div>
                            {{-- Badge catégorie --}}
                            <span class="absolute top-3 left-3 text-xs font-bold px-2 py-1 rounded-full"
                                  style="background: rgba(244,164,41,0.9); color: #2C1A0E">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        {{-- Infos produit --}}
                        <div class="p-5">
                            <h3 class="font-bold text-lg group-hover:text-yellow-400 transition-colors"
                                style="color: #FDF6EC">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm mt-1 line-clamp-2" style="color: rgba(253,246,236,0.6)">
                                {{ $product->description }}
                            </p>

                            <div class="flex items-center justify-between mt-5">
                                <span class="text-2xl font-black" style="color: #F4A429">
                                    {{ number_format($product->price, 0, ',', ' ') }} Ar
                                </span>

                                @auth
                                    @if(auth()->user()->isAcheteur())
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <button class="p-3 rounded-xl transition-all duration-300 active:scale-95"
                                                    style="background: rgba(244,164,41,0.15); border: 1px solid rgba(244,164,41,0.3); color: #F4A429"
                                                    onmouseover="this.style.background='#F4A429'; this.style.color='#2C1A0E'"
                                                    onmouseout="this.style.background='rgba(244,164,41,0.15)'; this.style.color='#F4A429'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="p-3 rounded-xl transition-all"
                                       style="background: rgba(244,164,41,0.15); border: 1px solid rgba(244,164,41,0.3); color: #F4A429">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</x-app-layout>