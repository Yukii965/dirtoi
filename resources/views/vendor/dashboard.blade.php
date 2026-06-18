<x-app-layout>
    <div class="py-12">
        <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem;
                    display:flex; flex-direction:column; gap:1.5rem;">

            <div style="text-align:center; margin-bottom:0.5rem;">
                <h2 class="text-3xl font-black"
                    style="font-family:'Playfair Display',serif; color:#F4A429">
                    Tableau de bord vendeur
                </h2>
                <p style="color:rgba(253,246,236,0.6); margin-top:0.25rem;">
                    Bienvenue, {{ auth()->user()->name }} —
                    @if(auth()->user()->isVendeurPro())
                        <span style="color:#4ade80">Entreprise</span>
                    @else
                        <span style="color:#F4A429">Particulier</span>
                    @endif
                </p>
            </div>

            {{-- Statistiques --}}
            @php
                $totalProduits = auth()->user()->products()->count();
                $commandesEnCours = \App\Models\OrderItem::whereHas('product', fn($q) => $q->where('user_id', auth()->id()))
                    ->whereHas('order', fn($q) => $q->whereIn('status', ['paye_retenu','accepte_vendeur','livraison_en_cours']))->count();
                $ventesTerminees = \App\Models\OrderItem::whereHas('product', fn($q) => $q->where('user_id', auth()->id()))
                    ->whereHas('order', fn($q) => $q->where('status', 'termine'))->count();
            @endphp

            <div id="vendor-stats">
                <div style="padding:1.5rem; border-radius:1rem; text-align:center;
                            background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color:#F4A429">{{ $totalProduits }}</div>
                    <div style="font-size:0.875rem; margin-top:0.25rem; color:rgba(253,246,236,0.7)">Produits publiés</div>
                </div>
                <div style="padding:1.5rem; border-radius:1rem; text-align:center;
                            background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color:#E07B2A">{{ $commandesEnCours }}</div>
                    <div style="font-size:0.875rem; margin-top:0.25rem; color:rgba(253,246,236,0.7)">En cours</div>
                </div>
                <div style="padding:1.5rem; border-radius:1rem; text-align:center;
                            background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color:#4ade80">{{ $ventesTerminees }}</div>
                    <div style="font-size:0.875rem; margin-top:0.25rem; color:rgba(253,246,236,0.7)">Ventes terminées</div>
                </div>
            </div>

            {{-- Mes produits --}}
            <div style="padding:1.5rem; border-radius:1rem;
                        background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.2)">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
                    <h3 class="text-lg font-bold" style="color:#F4A429">Mes produits</h3>
                    <a href="{{ route('vendor.sell') }}" class="btn-gasy"
                       style="font-size:0.875rem; padding:0.5rem 1rem; border-radius:0.75rem;">
                        + Ajouter un produit
                    </a>
                </div>

                @php $products = auth()->user()->products()->latest()->take(5)->get(); @endphp

                @if($products->isEmpty())
                    <p style="text-align:center; padding:1.5rem; color:rgba(253,246,236,0.5)">Aucun produit pour le moment.</p>
                @else
                    <div id="vendor-products-table">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(244,164,41,0.2)">
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Produit</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Prix</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Stock</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Statut</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr style="border-bottom:1px solid rgba(244,164,41,0.1)">
                                    <td class="py-3 text-center" style="color:#FDF6EC">{{ $product->name }}</td>
                                    <td class="py-3 text-center" style="color:#F4A429">{{ number_format($product->price, 0, ',', ' ') }} Ar</td>
                                    <td class="py-3 text-center" style="color:rgba(253,246,236,0.7)">{{ $product->stock }}</td>
                                    <td class="py-3 text-center">
                                        @if($product->product_status === 'actif')
                                            <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                         background:rgba(74,222,128,0.15); color:#4ade80;
                                                         border:1px solid rgba(74,222,128,0.3)">Actif</span>
                                        @elseif($product->product_status === 'en_attente')
                                            <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                         background:rgba(250,204,21,0.15); color:#facc15;
                                                         border:1px solid rgba(250,204,21,0.3)">En attente</span>
                                        @else
                                            <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                         background:rgba(239,68,68,0.15); color:#f87171;
                                                         border:1px solid rgba(239,68,68,0.3)">Suspendu</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <div style="display:flex; gap:0.5rem; justify-content:center;">
                                            <a href="{{ route('products.show', $product->slug) }}"
                                               style="font-size:0.7rem; padding:0.3rem 0.6rem; border-radius:0.5rem;
                                                      text-decoration:none; background:rgba(244,164,41,0.15);
                                                      color:#F4A429; border:1px solid rgba(244,164,41,0.3)">
                                                👁️ Voir
                                            </a>
                                            <form method="POST" action="{{ route('vendor.product.delete', $product->id) }}"
                                                  onsubmit="return confirm('Supprimer ce produit ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        style="font-size:0.7rem; padding:0.3rem 0.6rem; border-radius:0.5rem;
                                                               background:rgba(239,68,68,0.15); color:#f87171;
                                                               border:1px solid rgba(239,68,68,0.3); cursor:pointer;">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Commandes reçues --}}
            <div style="padding:1.5rem; border-radius:1rem;
                        background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold" style="color:#F4A429; margin-bottom:1rem;">Commandes reçues</h3>

                @php
                    $mesCommandes = \App\Models\OrderItem::whereHas('product', fn($q) => $q->where('user_id', auth()->id()))
                        ->with(['order.user', 'product'])->latest()->get();
                @endphp

                @if($mesCommandes->isEmpty())
                    <p style="text-align:center; padding:1.5rem; color:rgba(253,246,236,0.5)">📭 Aucune commande reçue.</p>
                @else
                    <div id="vendor-orders-table">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(244,164,41,0.2)">
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">#</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Produit</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Acheteur</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Montant</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Statut</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mesCommandes as $item)
                                <tr style="border-bottom:1px solid rgba(244,164,41,0.1)">
                                    <td class="py-3 text-center" style="color:rgba(253,246,236,0.5)">#{{ $item->order->id }}</td>
                                    <td class="py-3 text-center font-medium" style="color:#FDF6EC">{{ $item->product->name }}</td>
                                    <td class="py-3 text-center" style="color:rgba(253,246,236,0.7)">{{ $item->order->user->name }}</td>
                                    <td class="py-3 text-center" style="color:#F4A429">{{ number_format($item->order->total_price, 0, ',', ' ') }} Ar</td>
                                    <td class="py-3 text-center">
                                        @php
                                            $sLabels = [
                                                'paye_retenu'        => ['💰 En attente',  'rgba(96,165,250,0.15)',  '#60a5fa',  'rgba(96,165,250,0.3)'],
                                                'accepte_vendeur'    => ['✅ Acceptée',     'rgba(167,139,250,0.15)', '#a78bfa',  'rgba(167,139,250,0.3)'],
                                                'livraison_en_cours' => ['🚚 En livraison', 'rgba(250,204,21,0.15)',  '#facc15',  'rgba(250,204,21,0.3)'],
                                                'confirme_acheteur'  => ['📦 Confirmé',     'rgba(192,132,252,0.15)', '#c084fc',  'rgba(192,132,252,0.3)'],
                                                'termine'            => ['💚 Terminé',      'rgba(74,222,128,0.15)',  '#4ade80',  'rgba(74,222,128,0.3)'],
                                            ];
                                            [$sl, $sbg, $sc, $sbd] = $sLabels[$item->order->status] ?? [$item->order->status,'rgba(255,255,255,0.1)','rgba(253,246,236,0.5)','rgba(255,255,255,0.2)'];
                                        @endphp
                                        <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                     background:{{ $sbg }}; color:{{ $sc }}; border:1px solid {{ $sbd }}">
                                            {{ $sl }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div style="display:flex; flex-direction:column; gap:0.4rem; align-items:center;">
                                            @if($item->order->status === 'paye_retenu')
                                                <form method="POST" action="{{ route('order.accept', $item->order->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            style="font-size:0.7rem; padding:0.3rem 0.75rem; border-radius:0.5rem;
                                                                   cursor:pointer; background:rgba(96,165,250,0.15);
                                                                   color:#60a5fa; border:1px solid rgba(96,165,250,0.3)">
                                                        ✅ Accepter
                                                    </button>
                                                </form>
                                            @elseif($item->order->status === 'accepte_vendeur')
                                                <form method="POST" action="{{ route('order.start-delivery', $item->order->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            style="font-size:0.7rem; padding:0.3rem 0.75rem; border-radius:0.5rem;
                                                                   cursor:pointer; background:rgba(250,204,21,0.15);
                                                                   color:#facc15; border:1px solid rgba(250,204,21,0.3)">
                                                        🚚 En livraison
                                                    </button>
                                                </form>
                                            @elseif($item->order->status === 'livraison_en_cours')
                                                <span style="font-size:0.7rem; color:rgba(253,246,236,0.35); font-style:italic;">
                                                    Attente acheteur…
                                                </span>
                                            @elseif($item->order->status === 'confirme_acheteur')
                                                <form method="POST" action="{{ route('order.validate-code', $item->order->id) }}"
                                                      style="display:flex; gap:0.4rem; align-items:center;">
                                                    @csrf
                                                    <input type="text" name="code" placeholder="Code"
                                                           style="width:6rem; border-radius:0.5rem; padding:0.3rem 0.5rem;
                                                                  font-size:0.7rem; background:rgba(255,255,255,0.1);
                                                                  border:1px solid rgba(244,164,41,0.3); color:#FDF6EC; outline:none;">
                                                    <button type="submit"
                                                            style="font-size:0.7rem; padding:0.3rem 0.6rem; border-radius:0.5rem;
                                                                   cursor:pointer; background:rgba(74,222,128,0.15);
                                                                   color:#4ade80; border:1px solid rgba(74,222,128,0.3)">
                                                        Valider
                                                    </button>
                                                </form>
                                            @elseif($item->order->status === 'termine')
                                                <span style="font-size:0.7rem; color:#4ade80;">💚 Payé</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        #vendor-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
        #vendor-products-table, #vendor-orders-table { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        #vendor-products-table table, #vendor-orders-table table { min-width:520px; }
        @media (max-width: 640px) { #vendor-stats { grid-template-columns:1fr; } }
    </style>
</x-app-layout>