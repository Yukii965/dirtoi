<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-6">

            <h1 class="text-3xl font-black mb-8" style="font-family: 'Playfair Display', serif; color: #F4A429">
                🛒 Mon panier
            </h1>

            @if(session('cart') && count(session('cart')) > 0)
                @php $total = 0; @endphp

                {{-- Liste produits --}}
                <div class="space-y-4 mb-6">
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity']; @endphp
                        <div class="p-4 rounded-2xl flex items-center gap-4"
                             style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">

                            <img src="{{ asset('storage/' . ($details['image'] ?? '')) }}"
                                 class="w-20 h-20 object-cover rounded-xl shrink-0"
                                 onerror="this.src='https://placehold.co/80x80/5C3317/F4A429?text=IMG'">

                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold truncate" style="color: #FDF6EC">{{ $details['name'] }}</h3>
                                <p class="text-sm mt-1" style="color: #F4A429">
                                    {{ number_format($details['price'], 0, ',', ' ') }} Ar
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                    <form action="{{ route('cart.decrement', $id) }}" method="POST">
                                        @csrf
                                        <button class="w-7 h-7 rounded-lg text-sm font-bold"
                                                style="background: rgba(244,164,41,0.15); color: #F4A429">−</button>
                                    </form>
                                    <span class="w-6 text-center text-sm font-bold" style="color: #FDF6EC">
                                        {{ $details['quantity'] }}
                                    </span>
                                    <form action="{{ route('cart.increment', $id) }}" method="POST">
                                        @csrf
                                        <button class="w-7 h-7 rounded-lg text-sm font-bold"
                                                style="background: rgba(244,164,41,0.15); color: #F4A429">+</button>
                                    </form>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="font-black" style="color: #F4A429">
                                    {{ number_format($details['price'] * $details['quantity'], 0, ',', ' ') }} Ar
                                </p>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs px-2 py-1 rounded-lg"
                                            style="background: rgba(239,68,68,0.15); color: #f87171">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Résumé --}}
                <div class="p-6 rounded-2xl"
                     style="background: rgba(44,26,14,0.9); border: 1px solid rgba(244,164,41,0.3)">
                    <h2 class="text-lg font-bold mb-4" style="color: #F4A429">Résumé</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span style="color: rgba(253,246,236,0.6)">Articles</span>
                            <span style="color: #FDF6EC">{{ count(session('cart')) }}</span>
                        </div>
                        <div class="flex justify-between text-sm"
                             style="border-top: 1px solid rgba(244,164,41,0.1); padding-top: 0.75rem">
                            <span style="color: rgba(253,246,236,0.6)">Livraison</span>
                            <span class="text-green-400 text-sm">À la charge du vendeur</span>
                        </div>
                        <div class="flex justify-between font-black text-xl"
                             style="border-top: 1px solid rgba(244,164,41,0.2); padding-top: 0.75rem">
                            <span style="color: #FDF6EC">Total</span>
                            <span style="color: #F4A429">{{ number_format($total, 0, ',', ' ') }} Ar</span>
                        </div>
                    </div>
                    <p class="text-xs mb-4 text-center" style="color: rgba(253,246,236,0.4)">
                        🔒 Argent retenu jusqu'à livraison confirmée
                    </p>
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest text-center"
                       style="background: #F4A429; color: #2C1A0E">
                        Passer la commande →
                    </a>
                    <a href="{{ route('home') }}"
                       class="block text-center text-sm mt-3"
                       style="color: rgba(253,246,236,0.4)">
                        ← Continuer les achats
                    </a>
                </div>

            @else
                <div class="text-center py-20 rounded-2xl"
                     style="background: rgba(44,26,14,0.5); border: 2px dashed rgba(244,164,41,0.2)">
                    <div class="text-6xl mb-4">🛒</div>
                    <p class="text-xl font-bold mb-2" style="color: rgba(253,246,236,0.7)">Panier vide</p>
                    <a href="{{ route('home') }}" class="btn-gasy px-8 py-3 rounded-xl font-bold inline-block mt-4">
                        Voir les produits
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>