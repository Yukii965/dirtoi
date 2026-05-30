<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-6 space-y-6">

            <div class="text-center mb-8">
                <h2 class="text-3xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                    Tableau de bord vendeur
                </h2>
                <p class="mt-1" style="color: rgba(253,246,236,0.6)">
                    Bienvenue, {{ auth()->user()->name }} —
                    @if(auth()->user()->isVendeurPro())
                        <span class="text-green-400">Entreprise</span>
                    @else
                        <span class="text-yellow-400">Particulier</span>
                    @endif
                </p>
            </div>

            {{-- Statistiques --}}
            @php
                $totalProduits = auth()->user()->products()->count();

                $commandesEnCours = \App\Models\OrderItem::whereHas('product', function($q) {
                    $q->where('user_id', auth()->id());
                })->whereHas('order', function($q) {
                    $q->where('status', 'livraison_en_cours');
                })->count();

                $ventesTerminees = \App\Models\OrderItem::whereHas('product', function($q) {
                    $q->where('user_id', auth()->id());
                })->whereHas('order', function($q) {
                    $q->where('status', 'termine');
                })->count();
            @endphp

            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color: #F4A429">{{ $totalProduits }}</div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">Produits publiés</div>
                </div>
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color: #E07B2A">{{ $commandesEnCours }}</div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">En livraison</div>
                </div>
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black text-green-400">{{ $ventesTerminees }}</div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">Ventes terminées</div>
                </div>
            </div>

            {{-- Mes produits --}}
            <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold" style="color: #F4A429">Mes produits</h3>
                    <a href="{{ route('vendor.sell') }}" class="btn-gasy text-sm px-4 py-2 rounded-xl">
                        + Ajouter un produit
                    </a>
                </div>

                @php $products = auth()->user()->products()->latest()->take(5)->get(); @endphp

                @if($products->isEmpty())
                    <p class="text-center py-6" style="color: rgba(253,246,236,0.5)">Aucun produit pour le moment.</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.2)">
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Produit</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Prix</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Stock</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.1)">
                                <td class="py-3 text-center" style="color: #FDF6EC">{{ $product->name }}</td>
                                <td class="py-3 text-center" style="color: #F4A429">{{ number_format($product->price, 0, ',', ' ') }} Ar</td>
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.7)">{{ $product->stock }}</td>
                                <td class="py-3 text-center">
                                    @if($product->product_status === 'actif')
                                        <span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Actif</span>
                                    @elseif($product->product_status === 'en_attente')
                                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">En attente</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-400">Suspendu</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Commandes reçues --}}
            <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold mb-4" style="color: #F4A429">Commandes reçues</h3>

                @php
                    $mesCommandes = \App\Models\OrderItem::whereHas('product', function($q) {
                        $q->where('user_id', auth()->id());
                    })->with(['order.user', 'product'])->latest()->get();
                @endphp

                @if($mesCommandes->isEmpty())
                    <p class="text-center py-6" style="color: rgba(253,246,236,0.5)">📭 Aucune commande reçue.</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.2)">
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">#</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Produit</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Acheteur</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Montant</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Statut</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mesCommandes as $item)
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.1)">
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.5)">#{{ $item->order->id }}</td>
                                <td class="py-3 text-center font-medium" style="color: #FDF6EC">{{ $item->product->name }}</td>
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.7)">{{ $item->order->user->name }}</td>
                                <td class="py-3 text-center" style="color: #F4A429">{{ number_format($item->order->total_price, 0, ',', ' ') }} Ar</td>
                                <td class="py-3 text-center">
                                    @php
                                        $labels = [
                                            'paye_retenu'        => ['Payé', 'bg-blue-500/20 text-blue-400'],
                                            'livraison_en_cours' => ['En livraison 🚚', 'bg-yellow-500/20 text-yellow-400'],
                                            'confirme_acheteur'  => ['Confirmé ✅', 'bg-purple-500/20 text-purple-400'],
                                            'termine'            => ['Terminé 💰', 'bg-green-500/20 text-green-400'],
                                        ];
                                        [$label, $badge] = $labels[$item->order->status] ?? [$item->order->status, 'bg-gray-500/20 text-gray-400'];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    @if($item->order->status === 'confirme_acheteur')
                                        <form method="POST" action="{{ route('order.validate-code', $item->order->id) }}"
                                              class="flex gap-2 justify-center">
                                            @csrf
                                            <input type="text" name="code"
                                                   placeholder="Code livreur"
                                                   class="rounded-lg px-2 py-1 text-xs w-24 focus:outline-none"
                                                   style="background: rgba(255,255,255,0.1); border: 1px solid rgba(244,164,41,0.3); color: #FDF6EC">
                                            <button type="submit"
                                                    class="text-xs px-2 py-1 rounded-lg"
                                                    style="background: rgba(34,197,94,0.2); color: #4ade80; border: 1px solid rgba(34,197,94,0.3)">
                                                Valider
                                            </button>
                                        </form>
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