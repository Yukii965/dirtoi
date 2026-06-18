<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">

           {{-- Lémurien avec vraie photo --}}
            <div style="position:fixed;right:0;top:0;z-index:30;pointer-events:none;width:180px;height:100vh;overflow:hidden;">

                {{-- Arbre SVG réaliste --}}
                <svg style="position:absolute;top:0;left:0;width:180px;height:100%;" viewBox="0 0 180 1000" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="90" cy="70" rx="80" ry="68" fill="#1a5218"/>
                    <ellipse cx="55" cy="98" rx="58" ry="46" fill="#236e20"/>
                    <ellipse cx="125" cy="92" rx="55" ry="42" fill="#144d12"/>
                    <ellipse cx="90" cy="45" rx="65" ry="50" fill="#3d9438"/>
                    <ellipse cx="55" cy="68" rx="40" ry="30" fill="#4ab044" opacity="0.8"/>
                    <ellipse cx="125" cy="64" rx="42" ry="32" fill="#1e7019" opacity="0.75"/>
                    <ellipse cx="90" cy="82" rx="45" ry="34" fill="#144d12" opacity="0.65"/>
                    <path d="M76 108 Q72 400 74 700 Q75 860 76 1000 L104 1000 Q105 860 106 700 Q108 400 104 108Z" fill="#7a4520"/>
                    <path d="M82 108 Q79 400 80 700 Q81 860 81 1000 L90 1000 Q90 860 90 700 Q89 400 88 108Z" fill="#9a6035" opacity="0.5"/>
                    <path d="M76 200 Q78 212 104 200" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.6"/>
                    <path d="M76 300 Q78 312 104 300" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.55"/>
                    <path d="M76 400 Q78 412 104 400" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.5"/>
                    <path d="M76 500 Q78 512 104 500" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.5"/>
                    <path d="M76 600 Q78 612 104 600" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.45"/>
                    <path d="M76 700 Q78 712 104 700" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.4"/>
                    <path d="M76 800 Q78 812 104 800" stroke="#5a3010" stroke-width="1.5" fill="none" opacity="0.4"/>
                    <path d="M90 280 C58 262 28 250 4 240" stroke="#7a4520" stroke-width="13" fill="none" stroke-linecap="round"/>
                    <ellipse cx="2" cy="233" rx="24" ry="16" fill="#1a5218" transform="rotate(-22,2,233)"/>
                    <ellipse cx="-2" cy="241" rx="18" ry="12" fill="#2d7a28" transform="rotate(-32,-2,241)"/>
                    <path d="M90 480 C122 462 150 448 172 436" stroke="#7a4520" stroke-width="12" fill="none" stroke-linecap="round"/>
                    <ellipse cx="174" cy="429" rx="24" ry="16" fill="#144d12" transform="rotate(26,174,429)"/>
                    <ellipse cx="178" cy="437" rx="18" ry="12" fill="#1a5218" transform="rotate(16,178,437)"/>
                    <path d="M90 680 C58 664 28 652 4 642" stroke="#7a4520" stroke-width="11" fill="none" stroke-linecap="round"/>
                    <ellipse cx="2" cy="635" rx="22" ry="15" fill="#1e7019" transform="rotate(-19,2,635)"/>
                    <path d="M90 870 C120 855 148 844 170 836" stroke="#7a4520" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <ellipse cx="172" cy="829" rx="21" ry="14" fill="#236e20" transform="rotate(23,172,829)"/>
                </svg>

                {{-- Lémurien avec vraie image + animation --}}
                <div id="lemur-img-container" style="position:absolute;right:5px;bottom:80px;width:120px;height:140px;transition:none;">
                    <img id="lemur-photo"
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Lemur_catta_001.jpg/220px-Lemur_catta_001.jpg"
                        style="width:110px;height:auto;border-radius:8px;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.5));transform-origin:center bottom;"
                        alt="Lémurien Maki"/>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof gsap === 'undefined') return;

                const container = document.getElementById('lemur-img-container');
                const photo     = document.getElementById('lemur-photo');
                if (!container || !photo) return;

                const vh = window.innerHeight;

                // Position départ en bas
                gsap.set(container, { bottom: 60 });

                const tl = gsap.timeline({ repeat: -1 });

                // Montée progressive avec légère oscillation
                tl.to(container, {
                    bottom: vh - 180,
                    duration: 12,
                    ease: "power1.inOut",
                    onUpdate: function() {
                        const progress = this.progress();
                        // Légère oscillation gauche/droite simulant la grimpe
                        const sway = Math.sin(progress * Math.PI * 14) * 6;
                        gsap.set(container, { x: sway });
                        // Légère rotation simulant le mouvement
                        const rot = Math.sin(progress * Math.PI * 14) * 4;
                        gsap.set(photo, { rotation: rot });
                    }
                })
                // Pause en haut — regarde autour
                .to(photo, { rotation: 15, duration: 0.8, ease: "power2.inOut" })
                .to(photo, { rotation: -12, duration: 1.0, ease: "power2.inOut" })
                .to(photo, { rotation: 0, duration: 0.6, ease: "power2.inOut" })
                // Descente
                .to(container, {
                    bottom: 60,
                    duration: 8,
                    ease: "power2.inOut",
                    onUpdate: function() {
                        const sway = Math.sin(this.progress() * Math.PI * 10) * 5;
                        gsap.set(container, { x: sway });
                    }
                })
                // Pause en bas
                .to({}, { duration: 2 });

                // Animation de respiration continue
                gsap.to(photo, {
                    scaleX: 1.03,
                    scaleY: 0.97,
                    duration: 1.5,
                    ease: "sine.inOut",
                    yoyo: true,
                    repeat: -1
                });
            });
            </script>

            {{-- Hero Banner --}}
            <div id = "hero-banner" class = "relative rounded-3xl overflow-hidden mb-16 h-80 flex items-center shadow-2xl"
                 style = "border: 1px solid rgba(244,164,41,0.3); z-index: 1;">
                <img src = "/images/baobab-sunset.jpg"
                     class = "absolute inset-0 w-full h-full object-cover opacity-40">
                <div class = "absolute inset-0"
                     style = "background: linear-gradient(to right, rgba(44,26,14,0.95), rgba(44,26,14,0.6), transparent)">
                </div>
                <div class = "relative z-10 px-12">
                    <span class = "inline-block text-xs font-bold px-3 py-1 rounded-full mb-4"
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
                            <a href="{{ route('register') }}"
                               class="btn-gasy px-6 py-3 rounded-xl font-bold inline-block transition-all active:scale-95"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(244,164,41,0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                Commencer gratuitement
                            </a>
                            <a href="{{ route('login') }}"
                               class="px-6 py-3 rounded-xl font-bold inline-block transition-all active:scale-95"
                               style="border: 2px solid #F4A429; color: #F4A429"
                               onmouseover="this.style.background='#F4A429'; this.style.color='#2C1A0E'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(244,164,41,0.3)'"
                               onmouseout="this.style.background='transparent'; this.style.color='#F4A429'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                Se connecter
                            </a>
                        </div>
                    @endguest
                </div>
            </div>

            {{-- Titre section produits --}}
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold"
                    style="color: #F4A429; font-family: 'Playfair Display', serif">
                    Produits disponibles
                </h2>
                <span class="text-sm" style="color: rgba(253,246,236,0.5)">
                    {{ $products->total() }} produits
                </span>
            </div>

            {{-- Grille produits --}}
            @if($products->isEmpty())
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🛍️</div>
                    <p style="color: rgba(253,246,236,0.5)">Aucun produit disponible pour le moment.</p>
                </div>
            @else
                <div id="products-home-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                    <a href="{{ auth()->check() ? route('products.show', $product->slug) : route('login') }}"
                       class="gasy-card group overflow-hidden block">

                        <div class="overflow-hidden h-52 relative">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 alt="{{ $product->name }}">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                                 style="background: linear-gradient(to top, rgba(44,26,14,0.8), transparent)">
                            </div>
                            <span class="absolute top-3 left-3 text-xs font-bold px-2 py-1 rounded-full"
                                  style="background: rgba(244,164,41,0.9); color: #2C1A0E">
                                {{ $product->category->name }}
                            </span>
                        </div>

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
                                @endauth
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif

            {{-- Voir plus / Pagination --}}
            @guest
                <div class="mt-12 text-center">
                    <a href="{{ route('register') }}"
                       class="btn-gasy px-10 py-4 rounded-xl font-black text-lg inline-block transition-all active:scale-95"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(244,164,41,0.4)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        Voir plus de produits →
                    </a>
                    <p class="mt-3 text-sm" style="color: rgba(253,246,236,0.4)">
                        Inscrivez-vous gratuitement pour accéder à tous les produits
                    </p>
                </div>
            @else
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endguest

        </div>
    </div>
    <style>
        #hero-banner { height: clamp(160px, 35vw, 320px) !important; }
        #hero-banner h1 { font-size: clamp(1.5rem, 5vw, 3.75rem) !important; }
        #hero-banner p { font-size: clamp(0.85rem, 2vw, 1.25rem) !important; }
        #hero-banner .px-12 { padding-left: clamp(1rem, 4vw, 3rem) !important; padding-right: clamp(1rem, 4vw, 3rem) !important; }
        #products-home-grid { display: grid !important; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)) !important; gap: 1rem !important; }
        @media (max-width: 500px) {
            #products-home-grid { grid-template-columns: repeat(2, 1fr) !important; }
            #hero-banner .mt-6 { flex-direction: column !important; }
            #hero-banner .mt-6 a { width: 100% !important; text-align: center !important; }
        }
        /* ========================================
        DÉCOR ARBRE + LÉMURIEN — Responsive
        On réduit puis on cache le décor sur petits écrans
        pour ne pas recouvrir le contenu (hero, cartes produits)
        ======================================== */
        @media (max-width: 1024px) {
            /* Tablette : on réduit la largeur du décor */
            #products-home-grid ~ *,
            div[style*="width:180px;height:100vh"] {
                width: 90px !important;
            }
            div[style*="width:180px;height:100vh"] svg,
            div[style*="width:180px;height:100vh"] #lemur-img-container {
                transform: scale(0.6);
                transform-origin: top right;
            }
        }

        @media (max-width: 640px) {
            /* Mobile : on cache complètement le décor pour libérer l'espace */
            div[style*="width:180px;height:100vh"] {
                display: none !important;
            }
        }
    </style>
</x-app-layout>