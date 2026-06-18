<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-6">
            <h1 class="text-3xl font-black mb-8" style="font-family:'Playfair Display',serif;color:#F4A429">
                ✏️ Modifier le produit
            </h1>
            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3)">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-xs">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('vendor.product.update', $product->id) }}" method="POST"
                  enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PATCH')
                <div>
                    <label class="text-xs font-bold uppercase" style="color:rgba(244,164,41,0.8)">Nom du produit</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none"
                           style="background:rgba(255,255,255,0.08);border:1px solid rgba(244,164,41,0.2);color:#FDF6EC">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold uppercase" style="color:rgba(244,164,41,0.8)">Prix (Ar)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                               class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none"
                               style="background:rgba(255,255,255,0.08);border:1px solid rgba(244,164,41,0.2);color:#FDF6EC">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase" style="color:rgba(244,164,41,0.8)">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                               class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none"
                               style="background:rgba(255,255,255,0.08);border:1px solid rgba(244,164,41,0.2);color:#FDF6EC">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color:rgba(244,164,41,0.8)">Catégorie</label>
                    <select name="category_id" required class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none"
                            style="background:rgba(44,26,14,0.95);border:1px solid rgba(244,164,41,0.2);color:#FDF6EC">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color:rgba(244,164,41,0.8)">Description</label>
                    <textarea name="description" rows="4" required
                              class="w-full rounded-xl px-4 py-3 text-sm mt-1 focus:outline-none resize-none"
                              style="background:rgba(255,255,255,0.08);border:1px solid rgba(244,164,41,0.2);color:#FDF6EC">{{ old('description', $product->description) }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase" style="color:rgba(244,164,41,0.8)">Nouvelle image (optionnel)</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full rounded-xl px-4 py-3 text-sm mt-1"
                           style="background:rgba(255,255,255,0.08);border:1px solid rgba(244,164,41,0.2);color:rgba(253,246,236,0.7)">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-4 rounded-xl font-black text-sm uppercase"
                            style="background:#F4A429;color:#2C1A0E">
                        💾 Sauvegarder
                    </button>
                    <a href="{{ route('dashboard') }}" class="py-4 px-6 rounded-xl font-bold text-sm text-center"
                       style="background:rgba(255,255,255,0.08);color:rgba(253,246,236,0.6)">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>