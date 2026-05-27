<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-5xl mx-auto px-6">
            
            <h1 class="text-3xl font-black uppercase tracking-tighter italic mb-8">
                Registre des <span class="text-blue-500">Acquisitions</span>
            </h1>

            <div class="space-y-4">
                @forelse(auth()->user()->orders as $order)
                    <div class="bg-gray-900/40 border border-gray-800 hover:border-blue-500/30 p-6 rounded-3xl backdrop-blur-xl flex flex-col md:flex-row justify-between items-center gap-6 transition-all">
                        
                        <!-- Infos Produit -->
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <img src="{{ asset('storage/' . $order->product->image) }}" class="w-16 h-16 object-contain bg-gray-950 p-2 rounded-xl border border-gray-800">
                            <div>
                                <h3 class="font-bold text-lg">{{ $order->product->name }}</h3>
                                <p class="text-xs text-gray-500 font-mono">ID STRATÉGIQUE : DT-{{ $order->id }}-{{ $order->created_at->format('Y') }}</p>
                            </div>
                        </div>

                        <!-- Métriques -->
                        <div class="grid grid-cols-2 md:flex items-center gap-8 w-full md:w-auto text-sm">
                            <div>
                                <p class="text-[10px] text-gray-500 font-mono uppercase">Quantité</p>
                                <p class="font-bold">x{{ $order->quantity }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 font-mono uppercase">Prix Total</p>
                                <p class="font-black text-blue-400">{{ number_format($order->total_price, 0, '.', ' ') }} Ar</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 font-mono uppercase">Date de transfert</p>
                                <p class="text-gray-300 text-xs">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <span class="px-3 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/30 rounded-full font-mono text-xs uppercase tracking-widest">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <!-- Action : Laisser un avis -->
                        <div>
                            <a href="{{ route('products.show', $order->product->slug) }}#avis-section" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-500 text-xs font-mono uppercase tracking-wider rounded-xl transition-all">
                                Soumettre Rapport
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 border border-dashed border-gray-800 rounded-3xl">
                        <p class="text-gray-500 font-mono">Aucun flux de transaction enregistré dans votre base de données.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>