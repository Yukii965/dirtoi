<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="text-4xl font-black mb-10 tracking-tighter italic">SÉLECTION DU <span class="text-cyan-500">MODE DE RÈGLEMENT</span></h1>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid gap-6">
                    
                    <label class="relative group cursor-pointer">
                        <input type="radio" name="payment_method" value="digital" class="peer hidden" checked>
                        <div class="p-6 bg-gray-900/50 border-2 border-gray-800 rounded-3xl peer-checked:border-cyan-500 peer-checked:bg-cyan-500/5 transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-cyan-500/20 rounded-2xl text-cyan-400">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold">Paiement Digital</h3>
                                        <p class="text-gray-400 text-sm">MVola, AirtelMoney, Orange Money ou PayPal</p>
                                    </div>
                                </div>
                                <div class="w-6 h-6 border-2 border-gray-700 rounded-full peer-checked:bg-cyan-500"></div>
                            </div>
                        </div>
                    </label>

                    <label class="relative group cursor-pointer">
                        <input type="radio" name="payment_method" value="delivery" class="peer hidden">
                        <div class="p-6 bg-gray-900/50 border-2 border-gray-800 rounded-3xl peer-checked:border-yellow-500 peer-checked:bg-yellow-500/5 transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-yellow-500/20 rounded-2xl text-yellow-400">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold">Paiement à la livraison</h3>
                                        <p class="text-gray-400 text-sm">Réglez en espèces dès réception du colis</p>
                                    </div>
                                </div>
                                <div class="w-6 h-6 border-2 border-gray-700 rounded-full peer-checked:bg-yellow-500"></div>
                            </div>
                        </div>
                    </label>

                    <div class="mt-8 space-y-4 bg-gray-900/30 p-8 rounded-3xl border border-gray-800">
                        <h3 class="font-mono text-xs text-cyan-500 uppercase tracking-widest">Coordonnées de livraison</h3>
                        <input type="text" name="address" placeholder="Adresse exacte (Ex: Logement 123, Itaosy)" required
                               class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white">
                        <input type="text" name="phone" placeholder="Numéro de téléphone (+261...)" required
                               class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white">
                    </div>

                    <button type="submit" class="mt-8 w-full bg-cyan-500 text-gray-950 py-5 rounded-2xl font-black text-xl hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all transform active:scale-95 uppercase">
                        Confirmer la Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>