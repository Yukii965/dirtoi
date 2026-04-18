<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex items-center space-x-4 mb-10">
                <div class="h-10 w-2 bg-cyan-500 shadow-[0_0_15px_rgba(6,182,212,0.5)] rounded-full"></div>
                <h1 class="text-4xl font-black tracking-tighter uppercase">Votre <span class="text-cyan-400">Terminal</span> de Commande</h1>
            </div>

            <div class="flex flex-col lg:flex-row gap-10">
                <div class="flex-1 space-y-4">
                    @if(session('cart') && count(session('cart')) > 0)
                        @foreach(session('cart') as $id => $details)
                            <div class="group relative bg-gray-900/50 backdrop-blur-xl border border-gray-800 rounded-3xl p-6 hover:border-cyan-500/30 transition-all duration-300">
                                <div class="flex items-center flex-wrap md:flex-nowrap gap-6">
                                    
                                    <div class="relative h-28 w-28 shrink-0">
                                        <img src="{{ $details['image'] }}" class="h-full w-full object-cover rounded-2xl shadow-2xl">
                                        <div class="absolute inset-0 rounded-2xl border border-white/10"></div>
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $details['name'] }}</h3>
                                        <p class="text-cyan-500/80 font-mono text-sm uppercase tracking-widest mt-1">ID-SECURE: #00{{ $id }}</p>
                                        
                                        <div class="flex items-center mt-4 bg-gray-950/50 w-fit rounded-xl p-1 border border-gray-800">
                                            <form action="{{ route('cart.decrement', $id) }}" method="POST">
                                                @csrf
                                                <button class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 rounded-lg text-cyan-400 font-bold transition-all text-xl">-</button>
                                            </form>
                                            
                                            <span class="px-6 font-mono font-bold text-white">{{ $details['quantity'] }}</span>
                                            
                                            <form action="{{ route('cart.increment', $id) }}" method="POST">
                                                @csrf
                                                <button class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 rounded-lg text-cyan-400 font-bold transition-all text-xl">+</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end justify-between self-stretch">
                                        <span class="text-2xl font-black text-white italic">
                                            {{ number_format($details['price'] * $details['quantity'], 0, ',', ' ') }} Ar
                                        </span>
                                        
                                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="group/btn p-3 bg-red-500/10 hover:bg-red-500 rounded-xl text-red-500 hover:text-white transition-all duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-3xl">
                            <p class="text-gray-500 text-xl font-mono">PANIER_VIDE_ATTENTE_DONNEES...</p>
                            <a href="{{ route('home') }}" class="mt-6 inline-block bg-cyan-500 text-gray-950 px-8 py-3 rounded-full font-bold hover:shadow-[0_0_20px_rgba(6,182,212,0.6)] transition-all">RETOURNER AU HUB</a>
                        </div>
                    @endif
                </div>

                @if(session('cart') && count(session('cart')) > 0)
                <div class="w-full lg:w-96">
                    <div class="sticky top-8 bg-gray-900 border border-cyan-500/20 rounded-3xl p-8 shadow-[0_0_50px_rgba(0,0,0,0.5)]">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <span class="h-2 w-2 bg-cyan-500 rounded-full animate-ping"></span>
                            RÉSUMÉ TRANSACTION
                        </h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-400">
                                <span>Total Articles</span>
                                <span class="text-white font-mono">{{ count(session('cart')) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400 border-b border-gray-800 pb-4">
                                <span>Livraison (Antananarivo)</span>
                                <span class="text-green-400 font-mono">GRATUIT</span>
                            </div>
                            <div class="flex justify-between items-end pt-2">
                                <span class="text-lg font-bold">TOTAL</span>
                                <div class="text-right">
                                    @php $total = 0 @endphp
                                    @foreach((array) session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                    @endforeach
                                    <p class="text-3xl font-black text-cyan-400 tracking-tighter shadow-cyan-500">{{ number_format($total, 0, ',', ' ') }} Ar</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-gray-950 py-4 rounded-2xl font-black text-lg shadow-[0_0_30px_rgba(6,182,212,0.3)] transition-all transform hover:scale-[1.02]">
                            SÉCURISER LE PAIEMENT
                        </a>
                        
                        <p class="mt-6 text-[10px] text-center text-gray-500 font-mono uppercase tracking-tighter">
                            Cryptage AES-256 actif. Données protégées par DirToi Security.
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>