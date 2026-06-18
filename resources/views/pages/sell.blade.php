<x-app-layout>
    <div class="py-12">
        {{-- max-w-2xl → style inline, space-y-6 → flex+gap, grid-cols-2 → id+media query --}}
        <div style="max-width:42rem; margin:0 auto; padding:0 1.5rem;">

            {{-- En-tête --}}
            <div style="text-align:center; margin-bottom:2.5rem;">
                <h1 class="text-4xl font-black"
                    style="font-family:'Playfair Display',serif; color:#F4A429">
                    Vendre un produit
                </h1>
                <p class="text-sm" style="color:rgba(253,246,236,0.5); margin-top:0.5rem;">
                    Vendeur : {{ Auth::user()?->name }}
                    @if(Auth::user()?->isVendeurPro())
                        <span style="margin-left:0.5rem; padding:0.125rem 0.5rem; border-radius:9999px;
                                     font-size:0.75rem; background:rgba(34,197,94,0.2); color:#4ade80;">
                            Entreprise
                        </span>
                    @else
                        <span style="margin-left:0.5rem; padding:0.125rem 0.5rem; border-radius:9999px;
                                     font-size:0.75rem; background:rgba(244,164,41,0.2); color:#F4A429;">
                            Particulier — 5% commission
                        </span>
                    @endif
                </p>
            </div>

            {{-- Erreurs --}}
            @if($errors->any())
                <div style="margin-bottom:1.5rem; padding:1rem; border-radius:0.75rem;
                            background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3);">
                    @foreach($errors->all() as $error)
                        <p style="color:#f87171; font-size:0.875rem;">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Formulaire : display flex+gap remplace space-y-6 --}}
            <form action="{{ route('vendor.publish') }}" method="POST"
                  enctype="multipart/form-data"
                  style="display:flex; flex-direction:column; gap:1.5rem;">
                @csrf

                {{-- Nom du produit --}}
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:700;
                                  text-transform:uppercase; margin-bottom:0.5rem;
                                  color:rgba(244,164,41,0.8);">
                        Nom du produit
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           required placeholder="Ex: Samsung Galaxy S20 FE"
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                  font-size:0.875rem; color:#FDF6EC; outline:none;
                                  background:rgba(255,255,255,0.08);
                                  border:1px solid rgba(244,164,41,0.2); box-sizing:border-box;">
                </div>

                {{-- Prix et Catégorie côte à côte sur desktop, empilés sur mobile --}}
                <div id="sell-price-category">
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:700;
                                      text-transform:uppercase; margin-bottom:0.5rem;
                                      color:rgba(244,164,41,0.8);">
                            Prix (Ariary)
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               required placeholder="Ex: 500000"
                               style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                      font-size:0.875rem; color:#FDF6EC; outline:none;
                                      background:rgba(255,255,255,0.08);
                                      border:1px solid rgba(244,164,41,0.2); box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.75rem; font-weight:700;
                                      text-transform:uppercase; margin-bottom:0.5rem;
                                      color:rgba(244,164,41,0.8);">
                            Catégorie
                        </label>
                        <select name="category_id" required
                                style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                       font-size:0.875rem; color:#FDF6EC; outline:none;
                                       background:rgba(44,26,14,0.95);
                                       border:1px solid rgba(244,164,41,0.2); box-sizing:border-box;">
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
                    <label style="display:block; font-size:0.75rem; font-weight:700;
                                  text-transform:uppercase; margin-bottom:0.5rem;
                                  color:rgba(244,164,41,0.8);">
                        Description du produit
                    </label>
                    <textarea name="description" rows="4" required
                              placeholder="Décrivez votre produit en détail..."
                              style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                     font-size:0.875rem; color:#FDF6EC; outline:none; resize:none;
                                     background:rgba(255,255,255,0.08);
                                     border:1px solid rgba(244,164,41,0.2);
                                     box-sizing:border-box; font-family:inherit;">{{ old('description') }}</textarea>
                </div>

                {{-- Image --}}
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:700;
                                  text-transform:uppercase; margin-bottom:0.5rem;
                                  color:rgba(244,164,41,0.8);">
                        Image du produit
                    </label>
                    <input type="file" name="image" accept="image/*" required
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                  font-size:0.875rem; box-sizing:border-box;
                                  background:rgba(255,255,255,0.08);
                                  border:1px solid rgba(244,164,41,0.2);
                                  color:rgba(253,246,236,0.7);">
                    <p style="font-size:0.75rem; margin-top:0.25rem; color:rgba(253,246,236,0.4);">
                        Formats acceptés : JPG, PNG, WEBP
                    </p>
                </div>

                {{-- Stock --}}
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:700;
                                  text-transform:uppercase; margin-bottom:0.5rem;
                                  color:rgba(244,164,41,0.8);">
                        Quantité en stock
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}"
                           required min="1" placeholder="Ex: 5"
                           style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                  font-size:0.875rem; color:#FDF6EC; outline:none;
                                  background:rgba(255,255,255,0.08);
                                  border:1px solid rgba(244,164,41,0.2); box-sizing:border-box;">
                </div>

                {{-- Soumettre --}}
                <button type="submit"
                        style="width:100%; padding:1rem; border-radius:0.75rem; font-weight:900;
                               font-size:0.875rem; text-transform:uppercase; letter-spacing:0.05em;
                               background:#F4A429; color:#2C1A0E; border:none; cursor:pointer;">
                    📦 Soumettre le produit
                </button>

                <p style="text-align:center; font-size:0.75rem; color:rgba(253,246,236,0.4);">
                    ℹ️ Votre produit sera visible après validation par notre équipe
                </p>

            </form>
        </div>
    </div>

    <style>
        /* Prix + Catégorie : 2 colonnes sur desktop, 1 colonne sur mobile */
        #sell-price-category {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 500px) {
            #sell-price-category {
                grid-template-columns: 1fr;
            }
            input, select, textarea {
                font-size: 16px !important; /* évite le zoom auto sur iOS */
            }
        }
    </style>
</x-app-layout>