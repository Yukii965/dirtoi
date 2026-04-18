<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-3xl mx-auto px-6">
            
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-black tracking-tighter uppercase">Initialiser un <span class="text-yellow-500">Nouveau Produit</span></h1>
                <p class="text-gray-500 font-mono text-sm mt-2">Accès vendeur : {{ Auth::user()?->name ?? 'Invité' }}</p>
            </div>

            <form action="{{ route('vendor.publish') }}" method="POST" class="space-y-6 bg-gray-900/50 p-8 rounded-[2rem] border border-gray-800 backdrop-blur-xl">
                @csrf

                <div>
                    <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Désignation du produit</label>
                    <input type="text" name="name" required class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white placeholder-gray-700" placeholder="Ex: Processeur Quantum X-1">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Prix (Ariary)</label>
                        <input type="number" name="price" required class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white" placeholder="0.00">
                    </div>

                    <div>
                        <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Secteur</label>
                        <select name="category_id" class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Spécifications techniques</label>
                    <textarea name="description" rows="4" required class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white" placeholder="Décrivez votre technologie..."></textarea>
                </div>

                <div>
                    <label class="block font-mono text-xs text-cyan-500 uppercase mb-2">Lien de l'image (URL)</label>
                    <input type="url" name="image" required class="w-full bg-gray-950 border-gray-800 rounded-xl focus:border-cyan-500 text-white" placeholder="https://images.unsplash.com/...">
                </div>

                <button type="submit" class="w-full bg-yellow-500 text-gray-950 py-4 rounded-2xl font-black text-lg shadow-[0_0_20px_rgba(234,179,8,0.3)] hover:shadow-[0_0_40px_rgba(234,179,8,0.5)] transition-all transform hover:scale-[1.01] active:scale-95">
                    DIFFUSER SUR LE RÉSEAU
                </button>
            </form>
        </div>
    </div>
</x-app-layout>