<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-6">

            {{-- En-tête --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                    Vendre un produit
                </h1>
                <p class="mt-2 text-sm" style="color: rgba(253,246,236,0.5)">
                    Vendeur : {{ Auth::user()?->name }}
                    @if(Auth::user()?->isVendeurPro())
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs"
                              style="background: rgba(34,197,94,0.2); color: #4ade80">Entreprise</span>
                    @else
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs"
                              style="background: rgba(244,164,41,0.2); color: #F4A429">Particulier — 5% commission</span>
                    @endif
                </p>
            </div>

            {{-- Erreurs --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3)">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-sm">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Formulaire --}}
            <form action="{{ route('vendor.publish') }}" method="POST"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Nom du produit --}}
                <div>
                    <label class="block text-xs font-bold uppercase mb-2"
                           style="color: rgba(244,164,41,0.8)">
                        Nom du produit
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           required placeholder="Ex: Samsung Galaxy S20 FE"
                           class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
                </div>

                {{-- Prix et Catégorie --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2"
                               style="color: rgba(244,164,41,0.8)">
                            Prix (Ariary)
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               required placeholder="Ex: 500000"
                               class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                               style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase mb-2"
                               style="color: rgba(244,164,41,0.8)">
                            Catégorie
                        </label>
                        <select name="category_id" required
                                class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                                style="background: rgba(44,26,14,0.95); border: 1px solid rgba(244,164,41,0.2)">
                            <option value="">Choisir...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-bold uppercase mb-2"
                           style="color: rgba(244,164,41,0.8)">
                        Description du produit
                    </label>
                    <textarea name="description" rows="4" required
                              placeholder="Décrivez votre produit en détail..."
                              class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none resize-none"
                              style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">{{ old('description') }}</textarea>
                </div>

                {{-- Image --}}
                <div>
                    <label class="block text-xs font-bold uppercase mb-2"
                           style="color: rgba(244,164,41,0.8)">
                        Image du produit
                    </label>
                    <input type="file" name="image" accept="image/*" required
                           class="w-full rounded-xl px-4 py-3 text-sm"
                           style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2); color: rgba(253,246,236,0.7)">
                    <p class="text-xs mt-1" style="color: rgba(253,246,236,0.4)">
                        Formats acceptés : JPG, PNG, WEBP
                    </p>
                </div>

                {{-- Quantité en stock --}}
                <div>
                    <label class="block text-xs font-bold uppercase mb-2"
                        style="color: rgba(244,164,41,0.8)">
                        Quantité en stock
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}"
                        required min="1" placeholder="Ex: 5"
                        class="w-full rounded-xl px-4 py-3 text-white text-sm focus:outline-none"
                        style="background: rgba(255,255,255,0.08); border: 1px solid rgba(244,164,41,0.2)">
                </div>

                {{-- Bouton soumettre --}}
                <button type="submit"
                        class="w-full py-4 rounded-xl font-black text-sm uppercase tracking-widest transition-all active:scale-95"
                        style="background: #F4A429; color: #2C1A0E">
                    📦 Soumettre le produit
                </button>

                <p class="text-center text-xs" style="color: rgba(253,246,236,0.4)">
                    ℹ️ Votre produit sera visible après validation par notre équipe
                </p>

            </form>
        </div>
    </div>
</x-app-layout>