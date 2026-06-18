<x-app-layout>
<div class="py-12">
<div style="max-width:56rem; margin:0 auto; padding:0 1.5rem; display:flex; flex-direction:column; gap:1.5rem;">

    {{-- En-tête --}}
    <div style="text-align:center;">
        <h1 class="text-3xl font-black"
            style="font-family:'Playfair Display',serif; color:#F4A429">
            🏪 Personnaliser ma boutique
        </h1>
        <p style="color:rgba(253,246,236,0.5); margin-top:0.25rem; font-size:0.875rem;">
            Ce que vos clients voient quand ils visitent votre page
            <a href="{{ route('shop.vendor', $vendor->id) }}"
               target="_blank"
               style="color:#F4A429; text-decoration:none; margin-left:0.5rem;">
                Voir ma page publique →
            </a>
        </p>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div style="padding:0.75rem 1rem; border-radius:0.75rem;
                    background:rgba(74,222,128,0.1); border:1px solid rgba(74,222,128,0.3);
                    color:#4ade80; font-size:0.875rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="padding:0.75rem 1rem; border-radius:0.75rem;
                    background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3);
                    color:#f87171; font-size:0.875rem;">
            {{ session('error') }}
        </div>
    @endif

    {{-- === SECTION 1 : Paramètres de base (tous les vendeurs) === --}}
    <div style="padding:1.5rem; border-radius:1rem;
                background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.2)">

        <h2 class="font-bold" style="color:#F4A429; margin-bottom:1.25rem; font-size:1rem;">
            ⚙️ Paramètres de base
            <span style="font-size:0.7rem; font-weight:400; color:rgba(253,246,236,0.4); margin-left:0.5rem;">
                Gratuit pour tous les vendeurs
            </span>
        </h2>

        <form method="POST" action="{{ route('vendor.customize.base') }}"
              style="display:flex; flex-direction:column; gap:1rem;">
            @csrf

            {{-- Couleur principale --}}
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:700;
                              text-transform:uppercase; color:rgba(244,164,41,0.8); margin-bottom:0.5rem;">
                    🎨 Couleur principale de votre boutique
                </label>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <input type="color" name="primary_color"
                           value="{{ $customization->primary_color }}"
                           style="width:3rem; height:3rem; border-radius:0.5rem; border:2px solid rgba(244,164,41,0.3);
                                  cursor:pointer; background:none; padding:0.2rem;">
                    <input type="text" id="color-hex" value="{{ $customization->primary_color }}"
                           style="width:7rem; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem;
                                  background:rgba(255,255,255,0.08); border:1px solid rgba(244,164,41,0.2);
                                  color:#FDF6EC; outline:none; font-family:monospace;"
                           readonly>
                    {{-- Aperçu --}}
                    <div id="color-preview"
                         style="padding:0.5rem 1rem; border-radius:0.5rem; font-size:0.75rem; font-weight:700; color:#2C1A0E;
                                background:{{ $customization->primary_color }}">
                        Aperçu de votre couleur
                    </div>
                </div>
            </div>

            {{-- Bio --}}
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:700;
                              text-transform:uppercase; color:rgba(244,164,41,0.8); margin-bottom:0.5rem;">
                    ✍️ Description de votre boutique
                    <span style="font-weight:400; text-transform:none; color:rgba(253,246,236,0.4); margin-left:0.25rem;">(max 500 caractères)</span>
                </label>
                <textarea name="bio" rows="3" maxlength="500"
                          placeholder="Présentez votre boutique à vos clients..."
                          style="width:100%; border-radius:0.75rem; padding:0.75rem 1rem;
                                 font-size:0.875rem; resize:none; outline:none;
                                 background:rgba(255,255,255,0.08); border:1px solid rgba(244,164,41,0.2);
                                 color:#FDF6EC; font-family:inherit; box-sizing:border-box;">{{ $customization->bio }}</textarea>
            </div>

            <button type="submit"
                    style="align-self:flex-start; padding:0.6rem 1.5rem; border-radius:0.75rem;
                           font-weight:700; font-size:0.875rem; cursor:pointer;
                           background:#F4A429; color:#2C1A0E; border:none;">
                💾 Sauvegarder
            </button>
        </form>
    </div>

    {{-- === SECTION 2 : Bannière (tous les vendeurs) === --}}
    <div style="padding:1.5rem; border-radius:1rem;
                background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.2)">

        <h2 class="font-bold" style="color:#F4A429; margin-bottom:1.25rem; font-size:1rem;">
            🖼️ Photo bannière
            <span style="font-size:0.7rem; font-weight:400; color:rgba(253,246,236,0.4); margin-left:0.5rem;">
                Affichée en haut de votre page boutique
            </span>
        </h2>

        {{-- Aperçu bannière actuelle --}}
        @if($customization->banner_image)
            <div style="margin-bottom:1rem; border-radius:0.75rem; overflow:hidden; height:8rem;">
                <img src="{{ asset('storage/' . $customization->banner_image) }}"
                     style="width:100%; height:100%; object-fit:cover;"
                     alt="Bannière actuelle">
            </div>
        @else
            <div style="margin-bottom:1rem; border-radius:0.75rem; height:8rem;
                        display:flex; align-items:center; justify-content:center;
                        background:rgba(255,255,255,0.04); border:2px dashed rgba(244,164,41,0.2);">
                <span style="color:rgba(253,246,236,0.3); font-size:0.875rem;">Aucune bannière — une image générique est utilisée</span>
            </div>
        @endif

        <form method="POST" action="{{ route('vendor.customize.banner') }}"
              enctype="multipart/form-data"
              style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
            @csrf
            <input type="file" name="banner" accept="image/*"
                   style="flex:1; border-radius:0.75rem; padding:0.6rem 1rem; font-size:0.875rem;
                          background:rgba(255,255,255,0.08); border:1px solid rgba(244,164,41,0.2);
                          color:rgba(253,246,236,0.7); box-sizing:border-box; min-width:0;">
            <button type="submit"
                    style="padding:0.6rem 1.25rem; border-radius:0.75rem; font-weight:700;
                           font-size:0.875rem; cursor:pointer; white-space:nowrap;
                           background:rgba(244,164,41,0.15); color:#F4A429;
                           border:1px solid rgba(244,164,41,0.35);">
                📤 Mettre à jour
            </button>
        </form>
        <p style="font-size:0.7rem; color:rgba(253,246,236,0.3); margin-top:0.5rem;">
            JPG, PNG, WEBP — max 3 Mo — recommandé : 1200×300px
        </p>
    </div>

    {{-- === SECTION : Gestion & ordre des produits === --}}
    <div style="padding:1.5rem; border-radius:1rem;
                background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.2)">

        <h2 class="font-bold" style="color:#F4A429; margin-bottom:0.25rem; font-size:1rem;">
            📦 Gestion des produits
            <span style="font-size:0.7rem; font-weight:400; color:rgba(253,246,236,0.4); margin-left:0.5rem;">
                Choisissez l'ordre d'affichage dans votre boutique
            </span>
        </h2>
        <p style="font-size:0.8rem; color:rgba(253,246,236,0.4); margin-bottom:1.25rem;">
            Utilisez les flèches pour réorganiser. L'ordre sera visible par vos clients.
        </p>

        @php
            $vendorProducts = $vendor->products()->where('product_status', 'actif')->get();
            $rawOrder   = $customization->product_order;
            $savedOrder = is_array($rawOrder)
                ? $rawOrder
                : (json_decode((string) $rawOrder, true) ?? []);
            if (!empty($savedOrder)) {
                $vendorProducts = $vendorProducts->sortBy(function($p) use ($savedOrder) {
                    $pos = array_search($p->id, $savedOrder);
                    return $pos === false ? 9999 : $pos;
                })->values();
            }
        @endphp

        @if($vendorProducts->isEmpty())
            <div style="text-align:center; padding:2rem; border-radius:0.75rem;
                        border:2px dashed rgba(244,164,41,0.15); color:rgba(253,246,236,0.3); font-size:0.875rem;">
                Aucun produit actif.
                <a href="{{ route('vendor.sell') }}"
                style="display:block; margin-top:0.75rem; color:#F4A429; text-decoration:none; font-weight:700;">
                    + Ajouter un produit →
                </a>
            </div>
        @else
            <div x-data="{
                    products: {{ $vendorProducts->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price, 'image' => $p->image])->toJson() }},

                    moveUp(idx) {
                        if (idx > 0) {
                            const item = this.products.splice(idx, 1)[0];
                            this.products.splice(idx - 1, 0, item);
                            this.syncOrder();
                        }
                    },

                    moveDown(idx) {
                        if (idx < this.products.length - 1) {
                            const item = this.products.splice(idx, 1)[0];
                            this.products.splice(idx + 1, 0, item);
                            this.syncOrder();
                        }
                    },

                    syncOrder() {
                        const input = document.getElementById('order-save-input');
                        if (input) input.value = JSON.stringify(this.products.map(p => p.id));
                    }
                }"
                x-init="syncOrder()">

                {{-- Liste des produits --}}
                <div style="display:flex; flex-direction:column; gap:0.5rem; margin-bottom:1rem;">
                    <template x-for="(product, idx) in products" :key="product.id">
                        <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem;
                                    border-radius:0.75rem; background:rgba(255,255,255,0.04);
                                    border:1px solid rgba(244,164,41,0.12);">

                            <span style="font-size:0.7rem; font-weight:900; color:rgba(244,164,41,0.5);
                                        width:1.25rem; text-align:center; flex-shrink:0;"
                                x-text="idx + 1 + '.'"></span>

                            <img :src="'/storage/' + product.image"
                                style="width:2.5rem; height:2.5rem; border-radius:0.5rem;
                                        object-fit:cover; flex-shrink:0; border:1px solid rgba(244,164,41,0.2);">

                            <div style="flex:1; min-width:0;">
                                <div style="font-weight:700; color:#FDF6EC; font-size:0.875rem;
                                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                    x-text="product.name"></div>
                                <div style="font-size:0.75rem; color:#F4A429; font-weight:700;"
                                    x-text="new Intl.NumberFormat('fr-FR').format(product.price) + ' Ar'"></div>
                            </div>

                            <div style="display:flex; flex-direction:column; gap:0.2rem; flex-shrink:0;">
                                <button type="button" @click="moveUp(idx)"
                                        :disabled="idx === 0"
                                        :style="idx === 0 ? 'opacity:0.2; cursor:not-allowed;' : 'cursor:pointer;'"
                                        style="padding:0.2rem 0.5rem; border-radius:0.35rem; font-size:0.75rem;
                                            background:rgba(255,255,255,0.06);
                                            border:1px solid rgba(255,255,255,0.1); color:rgba(253,246,236,0.7);">
                                    ↑
                                </button>
                                <button type="button" @click="moveDown(idx)"
                                        :disabled="idx === products.length - 1"
                                        :style="idx === products.length - 1 ? 'opacity:0.2; cursor:not-allowed;' : 'cursor:pointer;'"
                                        style="padding:0.2rem 0.5rem; border-radius:0.35rem; font-size:0.75rem;
                                            background:rgba(255,255,255,0.06);
                                            border:1px solid rgba(255,255,255,0.1); color:rgba(253,246,236,0.7);">
                                    ↓
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Formulaire de sauvegarde --}}
                <form method="POST" action="{{ route('vendor.customize.product-order') }}">
                    @csrf
                    {{-- La valeur est écrite par syncOrder() à chaque déplacement ET à l'init --}}
                    <input type="hidden" id="order-save-input" name="order" value="">
                    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                        <button type="submit"
                                style="padding:0.6rem 1.5rem; border-radius:0.75rem; font-weight:700;
                                    font-size:0.875rem; cursor:pointer; border:none;
                                    background:#F4A429; color:#2C1A0E;">
                            💾 Sauvegarder l'ordre
                        </button>
                        <span style="font-size:0.75rem; color:rgba(253,246,236,0.35);">
                            Cet ordre sera immédiatement visible par vos clients
                        </span>
                    </div>
                </form>
            </div>
        @endif
    </div>

    {{-- === SECTION 3 : Blocs de contenu (PREMIUM uniquement) === --}}
    <div style="position:relative; padding:1.5rem; border-radius:1rem;
                background:rgba(44,26,14,0.85);
                border:1px solid {{ $isPremium ? 'rgba(244,164,41,0.4)' : 'rgba(255,255,255,0.1)' }}">

        <h2 class="font-bold" style="color:{{ $isPremium ? '#F4A429' : 'rgba(253,246,236,0.3)' }};
                                      margin-bottom:0.25rem; font-size:1rem;">
            ✨ Blocs de contenu personnalisés
            <span style="font-size:0.7rem; font-weight:700; padding:0.15rem 0.5rem; border-radius:9999px; margin-left:0.5rem;
                         background:rgba(244,164,41,0.9); color:#2C1A0E;">
                PREMIUM
            </span>
        </h2>
        <p style="font-size:0.8rem; color:rgba(253,246,236,0.4); margin-bottom:1.25rem;">
            Ajoutez jusqu'à 8 blocs (texte, image, bannière colorée) qui s'affichent sur votre page publique.
        </p>

        @if(!$isPremium)
            {{-- Overlay Premium --}}
            <div style="position:relative; padding:2rem; border-radius:0.75rem; text-align:center;
                        background:rgba(0,0,0,0.4); border:2px dashed rgba(244,164,41,0.3);">
                <div style="font-size:2.5rem; margin-bottom:0.75rem;">🔒</div>
                <h3 class="font-black" style="color:#FDF6EC; margin-bottom:0.5rem;">
                    Fonctionnalité Premium
                </h3>
                <p style="font-size:0.875rem; color:rgba(253,246,236,0.5); margin-bottom:1.25rem;">
                    Souscrivez à l'abonnement <strong style="color:#F4A429">Plus Plus</strong> pour débloquer les blocs personnalisés et rendre votre boutique unique.
                </p>
                <a href="{{ route('nav.help') }}"
                   style="display:inline-block; padding:0.75rem 2rem; border-radius:0.75rem;
                          font-weight:900; font-size:0.875rem; text-decoration:none;
                          background:#F4A429; color:#2C1A0E;">
                    🚀 Passer au Premium
                </a>
            </div>

        @else
            {{-- Éditeur de blocs Alpine.js --}}
            <div x-data="{
                blocks: {{ json_encode($customization->blocks ?? []) }},
                addBlock(type) {
                    const id = 'b_' + Date.now();
                    if (type === 'text')   this.blocks.push({id, type: 'text', content: ''});
                    if (type === 'image')  this.blocks.push({id, type: 'image', path: '', url: ''});
                    if (type === 'banner') this.blocks.push({id, type: 'banner', text: '', bg: '#F4A429'});
                },
                removeBlock(id) { this.blocks = this.blocks.filter(b => b.id !== id); },
                moveUp(idx)   { if (idx > 0) [this.blocks[idx-1], this.blocks[idx]] = [this.blocks[idx], this.blocks[idx-1]]; },
                moveDown(idx) { if (idx < this.blocks.length-1) [this.blocks[idx], this.blocks[idx+1]] = [this.blocks[idx+1], this.blocks[idx]]; },
                async uploadImage(event, block) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const form = new FormData();
                    form.append('image', file);
                    form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                    const res = await fetch('{{ route('vendor.customize.block-image') }}', {method:'POST', body:form});
                    const data = await res.json();
                    block.path = data.path;
                    block.url  = data.url;
                }
            }">

                {{-- Boutons ajouter un bloc --}}
                <div style="display:flex; gap:0.5rem; margin-bottom:1rem; flex-wrap:wrap;">
                    <button type="button" @click="addBlock('text')"
                            :disabled="blocks.length >= 8"
                            style="padding:0.45rem 0.9rem; border-radius:0.5rem; font-size:0.8rem;
                                   font-weight:700; cursor:pointer; border:none;
                                   background:rgba(96,165,250,0.15); color:#60a5fa;
                                   border:1px solid rgba(96,165,250,0.3);">
                        + Bloc texte
                    </button>
                    <button type="button" @click="addBlock('image')"
                            :disabled="blocks.length >= 8"
                            style="padding:0.45rem 0.9rem; border-radius:0.5rem; font-size:0.8rem;
                                   font-weight:700; cursor:pointer; border:none;
                                   background:rgba(74,222,128,0.15); color:#4ade80;
                                   border:1px solid rgba(74,222,128,0.3);">
                        + Bloc image
                    </button>
                    <button type="button" @click="addBlock('banner')"
                            :disabled="blocks.length >= 8"
                            style="padding:0.45rem 0.9rem; border-radius:0.5rem; font-size:0.8rem;
                                   font-weight:700; cursor:pointer; border:none;
                                   background:rgba(244,164,41,0.15); color:#F4A429;
                                   border:1px solid rgba(244,164,41,0.3);">
                        + Bloc bannière
                    </button>
                    <span style="font-size:0.75rem; color:rgba(253,246,236,0.35); line-height:2rem; margin-left:auto;"
                          x-text="blocks.length + '/8 blocs'"></span>
                </div>

                {{-- Liste des blocs --}}
                <div style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1rem;">
                    <template x-for="(block, idx) in blocks" :key="block.id">
                        <div style="padding:1rem; border-radius:0.75rem;
                                    background:rgba(255,255,255,0.05); border:1px solid rgba(244,164,41,0.15);">

                            {{-- En-tête du bloc --}}
                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;">
                                <span style="font-size:0.7rem; font-weight:700; padding:0.15rem 0.5rem;
                                             border-radius:0.25rem; background:rgba(244,164,41,0.15); color:#F4A429;"
                                      x-text="block.type === 'text' ? '📝 Texte' : block.type === 'image' ? '🖼️ Image' : '🎨 Bannière'">
                                </span>
                                <div style="display:flex; gap:0.25rem; margin-left:auto;">
                                    <button type="button" @click="moveUp(idx)"
                                            style="padding:0.2rem 0.5rem; border-radius:0.35rem; font-size:0.7rem;
                                                   cursor:pointer; background:rgba(255,255,255,0.05);
                                                   border:1px solid rgba(255,255,255,0.1); color:rgba(253,246,236,0.6);">↑</button>
                                    <button type="button" @click="moveDown(idx)"
                                            style="padding:0.2rem 0.5rem; border-radius:0.35rem; font-size:0.7rem;
                                                   cursor:pointer; background:rgba(255,255,255,0.05);
                                                   border:1px solid rgba(255,255,255,0.1); color:rgba(253,246,236,0.6);">↓</button>
                                    <button type="button" @click="removeBlock(block.id)"
                                            style="padding:0.2rem 0.5rem; border-radius:0.35rem; font-size:0.7rem;
                                                   cursor:pointer; background:rgba(239,68,68,0.1);
                                                   border:1px solid rgba(239,68,68,0.2); color:#f87171;">✕</button>
                                </div>
                            </div>

                            {{-- Contenu selon le type --}}
                            {{-- Bloc texte --}}
                            <template x-if="block.type === 'text'">
                                <textarea x-model="block.content" rows="3"
                                          placeholder="Écrivez votre texte..."
                                          style="width:100%; border-radius:0.5rem; padding:0.6rem 0.75rem;
                                                 font-size:0.875rem; resize:none; outline:none;
                                                 background:rgba(255,255,255,0.08); border:1px solid rgba(244,164,41,0.15);
                                                 color:#FDF6EC; font-family:inherit; box-sizing:border-box;"></textarea>
                            </template>

                            {{-- Bloc image --}}
                            <template x-if="block.type === 'image'">
                                <div>
                                    <div x-show="block.url" style="margin-bottom:0.5rem; border-radius:0.5rem; overflow:hidden; height:6rem;">
                                        <img :src="block.url" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                    <input type="file" accept="image/*"
                                           @change="uploadImage($event, block)"
                                           style="width:100%; font-size:0.8rem; color:rgba(253,246,236,0.6);">
                                </div>
                            </template>

                            {{-- Bloc bannière --}}
                            <template x-if="block.type === 'banner'">
                                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                                    <input type="text" x-model="block.text"
                                           placeholder="Texte de la bannière..."
                                           style="width:100%; border-radius:0.5rem; padding:0.6rem 0.75rem;
                                                  font-size:0.875rem; outline:none; box-sizing:border-box;
                                                  background:rgba(255,255,255,0.08); border:1px solid rgba(244,164,41,0.15);
                                                  color:#FDF6EC;">
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <label style="font-size:0.75rem; color:rgba(253,246,236,0.5);">Couleur de fond :</label>
                                        <input type="color" x-model="block.bg"
                                               style="width:2.5rem; height:2rem; border-radius:0.4rem; border:none;
                                                      cursor:pointer; padding:0.1rem;">
                                        {{-- Aperçu --}}
                                        <div :style="'padding:0.35rem 1rem; border-radius:0.5rem; font-size:0.75rem; font-weight:700; color:#2C1A0E; background:' + block.bg"
                                             x-text="block.text || 'Aperçu'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="blocks.length === 0">
                        <div style="text-align:center; padding:2rem; border-radius:0.75rem;
                                    border:2px dashed rgba(244,164,41,0.15); color:rgba(253,246,236,0.3);
                                    font-size:0.875rem;">
                            Aucun bloc — cliquez sur "+ Bloc texte", "+ Bloc image" ou "+ Bloc bannière" pour commencer
                        </div>
                    </template>
                </div>

                {{-- Sauvegarder les blocs --}}
                <form id="blocks-save-form" method="POST" action="{{ route('vendor.customize.blocks') }}">
                    @csrf
                    <input type="hidden" id="blocks-save-input" name="blocks" value="">
                    <button type="button"
                            @click="
                                document.getElementById('blocks-save-input').value = JSON.stringify(blocks);
                                document.getElementById('blocks-save-form').submit();
                            "
                            style="padding:0.6rem 1.5rem; border-radius:0.75rem; font-weight:700;
                                font-size:0.875rem; cursor:pointer; background:#F4A429;
                                color:#2C1A0E; border:none;">
                        💾 Sauvegarder les blocs
                    </button>
                </form>
            </div>
        @endif
    </div>

</div>
</div>

<script>
    // Synchronise le color picker avec la case hex et l'aperçu
    document.addEventListener('DOMContentLoaded', function() {
        const picker  = document.querySelector('input[type=color][name=primary_color]');
        const hexBox  = document.getElementById('color-hex');
        const preview = document.getElementById('color-preview');
        if (!picker) return;
        picker.addEventListener('input', function() {
            hexBox.value        = picker.value;
            preview.style.background = picker.value;
        });
    });
</script>
</x-app-layout>