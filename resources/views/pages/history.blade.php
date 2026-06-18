<x-app-layout>
    <div class="py-12">
        <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem;">

            <div style="text-align:center; margin-bottom:2rem;">
                <h1 class="text-3xl font-black"
                    style="font-family:'Playfair Display',serif; color:#F4A429">
                    📋 Historique des commandes
                </h1>
                <p style="color:rgba(253,246,236,0.5); margin-top:0.25rem; font-size:0.875rem;">
                    Toutes vos transactions sur GasyMarket
                </p>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                @forelse(auth()->user()->orders()->with('items.product')->latest()->get() as $order)
                    <div style="padding:1.25rem 1.5rem; border-radius:1rem;
                                background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.15);">

                        <div id="history-row-{{ $order->id }}">

                            {{-- Image + infos produit --}}
                            <div style="display:flex; align-items:center; gap:1rem; flex:1; min-width:0;">
                                @if($order->items->first()?->product?->image)
                                    <img src="{{ asset('storage/' . $order->items->first()->product->image) }}"
                                         style="width:3.5rem; height:3.5rem; object-fit:cover;
                                                border-radius:0.75rem; flex-shrink:0;
                                                border:1px solid rgba(244,164,41,0.2);"
                                         alt="produit">
                                @endif
                                <div style="min-width:0;">
                                    <h3 class="font-bold"
                                        style="color:#FDF6EC; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $order->items->first()?->product?->name ?? 'Produit supprimé' }}
                                    </h3>
                                    <p style="font-size:0.7rem; color:rgba(253,246,236,0.4); margin-top:0.15rem;">
                                        Commande #{{ $order->id }} · {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Métriques --}}
                            <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                                <div style="text-align:center;">
                                    <p style="font-size:0.65rem; color:rgba(253,246,236,0.4); text-transform:uppercase; margin-bottom:0.2rem;">Quantité</p>
                                    <p class="font-bold" style="color:#FDF6EC;">x{{ $order->quantity ?? 1 }}</p>
                                </div>
                                <div style="text-align:center;">
                                    <p style="font-size:0.65rem; color:rgba(253,246,236,0.4); text-transform:uppercase; margin-bottom:0.2rem;">Total</p>
                                    <p class="font-black" style="color:#F4A429;">{{ number_format($order->total_price, 0, ',', ' ') }} Ar</p>
                                </div>
                                <div>
                                    @php
                                        $hLabels = [
                                            'paye_retenu'        => ['Payé 🔒',         'rgba(96,165,250,0.15)',  '#60a5fa', 'rgba(96,165,250,0.3)'],
                                            'accepte_vendeur'    => ['Acceptée',         'rgba(167,139,250,0.15)', '#a78bfa', 'rgba(167,139,250,0.3)'],
                                            'livraison_en_cours' => ['En livraison 🚚',  'rgba(250,204,21,0.15)',  '#facc15', 'rgba(250,204,21,0.3)'],
                                            'confirme_acheteur'  => ['Confirmé 📦',      'rgba(192,132,252,0.15)', '#c084fc', 'rgba(192,132,252,0.3)'],
                                            'termine'            => ['Terminé 💚',       'rgba(74,222,128,0.15)',  '#4ade80', 'rgba(74,222,128,0.3)'],
                                            'annule'             => ['Annulé',           'rgba(239,68,68,0.15)',   '#f87171', 'rgba(239,68,68,0.3)'],
                                        ];
                                        [$hl, $hbg, $hc, $hbd] = $hLabels[$order->status] ?? [$order->status,'rgba(255,255,255,0.1)','rgba(253,246,236,0.5)','rgba(255,255,255,0.2)'];
                                    @endphp
                                    <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.7rem; font-weight:700;
                                                 background:{{ $hbg }}; color:{{ $hc }}; border:1px solid {{ $hbd }}">
                                        {{ $hl }}
                                    </span>
                                </div>
                                @if($order->items->first()?->product)
                                    <a href="{{ route('products.show', $order->items->first()->product->slug) }}"
                                       style="font-size:0.75rem; padding:0.35rem 0.75rem; border-radius:0.5rem;
                                              text-decoration:none; white-space:nowrap;
                                              background:rgba(244,164,41,0.12); color:#F4A429;
                                              border:1px solid rgba(244,164,41,0.25);">
                                        Voir le produit →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding:4rem 1rem; border-radius:1rem;
                                background:rgba(44,26,14,0.5); border:2px dashed rgba(244,164,41,0.2)">
                        <div style="font-size:3rem; margin-bottom:1rem;">📭</div>
                        <p style="color:rgba(253,246,236,0.5)">Aucune commande enregistrée.</p>
                        <a href="{{ route('home') }}"
                           style="display:inline-block; margin-top:1rem; padding:0.5rem 1.5rem;
                                  border-radius:0.75rem; font-size:0.875rem; font-weight:700;
                                  background:rgba(244,164,41,0.15); color:#F4A429; text-decoration:none;">
                            Découvrir les produits
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <style>
        [id^="history-row-"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
    </style>
</x-app-layout>