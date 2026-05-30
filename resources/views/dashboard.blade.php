<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-6 space-y-6">

            {{-- En-tête --}}
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                    Mon espace acheteur
                </h2>
                <p class="mt-1" style="color: rgba(253,246,236,0.6)">
                    Bienvenue, {{ auth()->user()->name }} 👋
                </p>
            </div>

            {{-- Mes commandes --}}
            <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold mb-4" style="color: #F4A429">
                    Mes commandes récentes
                </h3>

                @php
                    $orders = auth()->user()->orders()->latest()->take(5)->get();
                @endphp

                @if($orders->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-4xl mb-3">🛍️</div>
                        <p style="color: rgba(253,246,236,0.5)">Vous n'avez pas encore passé de commande.</p>
                        <a href="{{ route('home') }}" class="mt-4 inline-block text-sm" style="color: #F4A429">
                            Découvrir les produits →
                        </a>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.2)">
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Commande</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Montant</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Statut</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Date</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.1)">
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.5)">#{{ $order->id }}</td>
                                <td class="py-3 text-center" style="color: #F4A429">
                                    {{ number_format($order->total_price, 0, ',', ' ') }} Ar
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $labels = [
                                            'en_attente_paiement' => ['En attente', 'bg-gray-500/20 text-gray-400'],
                                            'paye_retenu'         => ['Payé 🔒', 'bg-blue-500/20 text-blue-400'],
                                            'livraison_en_cours'  => ['En livraison 🚚', 'bg-yellow-500/20 text-yellow-400'],
                                            'confirme_acheteur'   => ['Confirmé ✅', 'bg-purple-500/20 text-purple-400'],
                                            'code_envoye_livreur' => ['Code envoyé', 'bg-orange-500/20 text-orange-400'],
                                            'termine'             => ['Terminé 💰', 'bg-green-500/20 text-green-400'],
                                            'annule'              => ['Annulé', 'bg-red-500/20 text-red-400'],
                                            'litige'              => ['Litige ⚠️', 'bg-red-700/20 text-red-300'],
                                        ];
                                        [$label, $badge] = $labels[$order->status] ?? [$order->status, 'bg-gray-500/20 text-gray-400'];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.5)">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 text-center">
                                    {{-- Bouton confirmer réception --}}
                                    @if($order->status === 'livraison_en_cours')
                                        <form method="POST" action="{{ route('order.confirm', $order->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs px-3 py-1 rounded-lg font-medium"
                                                style="background: rgba(34,197,94,0.2); color: #4ade80; border: 1px solid rgba(34,197,94,0.3)">
                                                ✅ Confirmer réception
                                            </button>
                                        </form>
                                    @elseif($order->status === 'confirme_acheteur' && $order->delivery_code)
                                        {{-- Affiche le code à donner au livreur --}}
                                        <div class="text-center">
                                            <p class="text-xs mb-1" style="color: rgba(253,246,236,0.5)">Code livreur :</p>
                                            <span class="text-sm font-black px-3 py-1 rounded-lg"
                                                style="background: rgba(244,164,41,0.2); color: #F4A429; border: 1px solid rgba(244,164,41,0.3); letter-spacing: 0.1em">
                                                {{ $order->delivery_code }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>