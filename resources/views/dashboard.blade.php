<x-app-layout>
    <div class="py-12">
        <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem;
                    display:flex; flex-direction:column; gap:1.5rem;">

            <div style="text-align:center; margin-bottom:0.5rem;">
                <h2 class="text-3xl font-black"
                    style="font-family:'Playfair Display',serif; color:#F4A429">
                    Mon espace acheteur
                </h2>
                <p style="color:rgba(253,246,236,0.6); margin-top:0.25rem;">
                    Bienvenue, {{ auth()->user()->name }} 👋
                </p>
            </div>

            <div style="padding:1.5rem; border-radius:1rem;
                        background:rgba(44,26,14,0.85); border:1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold" style="color:#F4A429; margin-bottom:1rem;">
                    Mes commandes récentes
                </h3>

                @php $orders = auth()->user()->orders()->latest()->take(5)->get(); @endphp

                @if($orders->isEmpty())
                    <div style="text-align:center; padding:2rem;">
                        <div style="font-size:2.5rem; margin-bottom:0.75rem;">🛍️</div>
                        <p style="color:rgba(253,246,236,0.5)">Vous n'avez pas encore passé de commande.</p>
                        <a href="{{ route('home') }}"
                           style="display:inline-block; margin-top:1rem; font-size:0.875rem;
                                  color:#F4A429; text-decoration:none;">
                            Découvrir les produits →
                        </a>
                    </div>
                @else
                    <div id="buyer-orders-table">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="border-bottom:1px solid rgba(244,164,41,0.2)">
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">#</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Montant</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Statut</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Date</th>
                                    <th class="pb-3 text-center" style="color:rgba(244,164,41,0.7)">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr style="border-bottom:1px solid rgba(244,164,41,0.1)">
                                    <td class="py-3 text-center" style="color:rgba(253,246,236,0.5)">#{{ $order->id }}</td>
                                    <td class="py-3 text-center" style="color:#F4A429">
                                        {{ number_format($order->total_price, 0, ',', ' ') }} Ar
                                    </td>
                                    <td class="py-3 text-center">
                                        @php
                                            $labels = [
                                                'en_attente_paiement' => ['En attente',       'rgba(156,163,175,0.15)', '#9ca3af', 'rgba(156,163,175,0.3)'],
                                                'paye_retenu'         => ['Payé 🔒',           'rgba(96,165,250,0.15)',  '#60a5fa', 'rgba(96,165,250,0.3)'],
                                                'accepte_vendeur'     => ['Acceptée ✅',       'rgba(167,139,250,0.15)', '#a78bfa', 'rgba(167,139,250,0.3)'],
                                                'livraison_en_cours'  => ['En livraison 🚚',   'rgba(250,204,21,0.15)',  '#facc15', 'rgba(250,204,21,0.3)'],
                                                'confirme_acheteur'   => ['Confirmé 📦',       'rgba(192,132,252,0.15)', '#c084fc', 'rgba(192,132,252,0.3)'],
                                                'termine'             => ['Terminé 💚',        'rgba(74,222,128,0.15)',  '#4ade80', 'rgba(74,222,128,0.3)'],
                                                'annule'              => ['Annulé',            'rgba(239,68,68,0.15)',   '#f87171', 'rgba(239,68,68,0.3)'],
                                                'litige'              => ['Litige ⚠️',         'rgba(239,68,68,0.2)',    '#fca5a5', 'rgba(239,68,68,0.4)'],
                                            ];
                                            [$label, $bg, $color, $border] = $labels[$order->status] ?? [$order->status,'rgba(255,255,255,0.1)','rgba(253,246,236,0.5)','rgba(255,255,255,0.2)'];
                                        @endphp
                                        <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                     background:{{ $bg }}; color:{{ $color }}; border:1px solid {{ $border }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center" style="color:rgba(253,246,236,0.5)">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($order->status === 'livraison_en_cours')
                                            <form method="POST" action="{{ route('order.confirm', $order->id) }}">
                                                @csrf
                                                <button type="submit"
                                                        style="font-size:0.7rem; padding:0.3rem 0.75rem; border-radius:0.5rem;
                                                               cursor:pointer; background:rgba(74,222,128,0.15);
                                                               color:#4ade80; border:1px solid rgba(74,222,128,0.3)">
                                                    ✅ Confirmer réception
                                                </button>
                                            </form>
                                        @elseif($order->status === 'confirme_acheteur' && $order->delivery_code)
                                            <div style="text-align:center;">
                                                <p style="font-size:0.7rem; color:rgba(253,246,236,0.5); margin-bottom:0.25rem;">
                                                    Code livreur :
                                                </p>
                                                <span style="font-size:0.875rem; font-weight:900; padding:0.3rem 0.75rem;
                                                             border-radius:0.5rem; letter-spacing:0.1em;
                                                             background:rgba(244,164,41,0.2); color:#F4A429;
                                                             border:1px solid rgba(244,164,41,0.3)">
                                                    {{ $order->delivery_code }}
                                                </span>
                                            </div>
                                        @endif
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
        #buyer-orders-table { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        #buyer-orders-table table { min-width:550px; }
    </style>
</x-app-layout>