<x-app-layout>
    <div class="py-12">
        <div style="max-width:64rem; margin:0 auto; padding:0 1.5rem; display:flex; flex-direction:column; gap:1.5rem;">

            {{-- En-tête --}}
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black" style="font-family: 'Playfair Display', serif; color: #F4A429">
                    🔧 Tableau de bord Admin
                </h2>
                <p class="mt-1" style="color: rgba(253,246,236,0.6)">
                    GasyMarket — Vue globale de la plateforme
                </p>
            </div>

            {{-- Statistiques globales --}}
            <div id = "admin-stats" class = "grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color: #F4A429">
                        {{ \App\Models\User::count() }}
                    </div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">Utilisateurs</div>
                </div>
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black" style="color: #E07B2A">
                        {{ \App\Models\User::whereIn('role', ['vendeur_amateur', 'vendeur_pro'])->count() }}
                    </div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">Vendeurs</div>
                </div>
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black text-green-400">
                        {{ \App\Models\Product::count() }}
                    </div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">Produits</div>
                </div>
                <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                    <div class="text-4xl font-black text-purple-400">
                        {{ \App\Models\Order::where('status', '!=', 'termine')->count() }}
                    </div>
                    <div class="text-sm mt-1" style="color: rgba(253,246,236,0.7)">Commandes actives</div>
                </div>
            </div>

            {{-- Revenus totaux --}}
            <div class="p-6 rounded-2xl text-center" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.3)">
                <div class="text-sm mb-1" style="color: rgba(253,246,236,0.6)">Commissions totales collectées</div>
                <div class="text-4xl font-black" style="color: #F4A429">
                    {{ number_format(\App\Models\Commission::sum('commission_amount'), 0, ',', ' ') }} Ar
                </div>
            </div>

            {{-- Produits en attente de validation --}}
            <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold mb-4" style="color: #F4A429">
                    Produits en attente de validation
                </h3>

                @php
                    $pendingProducts = \App\Models\Product::where('product_status', 'en_attente')
                                        ->with('user')->latest()->get();
                @endphp

                @if($pendingProducts->isEmpty())
                    <p class="text-center py-6" style="color: rgba(253,246,236,0.5)">
                        ✅ Aucun produit en attente.
                    </p>
                @else
                    <div id="admin-pending-table">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(244,164,41,0.2)">
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Produit</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Vendeur</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Prix</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Date</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingProducts as $product)
                                <tr style="border-bottom: 1px solid rgba(244,164,41,0.1)">
                                    <td class="py-3 text-center font-medium" style="color: #FDF6EC">{{ $product->name }}</td>
                                    <td class="py-3 text-center" style="color: rgba(253,246,236,0.7)">{{ $product->user->name }}</td>
                                    <td class="py-3 text-center" style="color: #F4A429">{{ number_format($product->price, 0, ',', ' ') }} Ar</td>
                                    <td class="py-3 text-center" style="color: rgba(253,246,236,0.5)">{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 text-center">
                                        {{-- Bouton valider le produit --}}
                                        <form method="POST" action="{{ route('admin.product.approve', $product->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs px-3 py-1 rounded-lg font-medium"
                                                style="background: rgba(34,197,94,0.2); color: #4ade80; border: 1px solid rgba(34,197,94,0.3)">
                                                ✅ Valider
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>    
                @endif
            </div>

            {{-- Comptes en attente de validation --}}
            <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold mb-4" style="color: #F4A429">
                    Comptes en attente de validation
                </h3>

                @php
                    $pendingUsers = \App\Models\User::where('status', 'en_attente')->latest()->get();
                @endphp

                @if($pendingUsers->isEmpty())
                    <p class="text-center py-4" style="color: rgba(253,246,236,0.5)">
                        ✅ Aucun compte en attente.
                    </p>
                @else
                    <div id="admin-users-table">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(244,164,41,0.2)">
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Nom</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Email</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Rôle</th>
                                    <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingUsers as $user)
                                <tr style="border-bottom: 1px solid rgba(244,164,41,0.1)">
                                    <td class="py-3 text-center" style="color: #FDF6EC">{{ $user->name }}</td>
                                    <td class="py-3 text-center" style="color: rgba(253,246,236,0.7)">{{ $user->email }}</td>
                                    <td class="py-3 text-center">
                                        <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                background:rgba(250,204,21,0.15); color:#facc15; border:1px solid rgba(250,204,21,0.3)">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <form method="POST" action="{{ route('admin.user.approve', $user->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs px-3 py-1 rounded-lg mr-2"
                                                    style="background: rgba(34,197,94,0.2); color: #4ade80; border: 1px solid rgba(34,197,94,0.3)">
                                                ✅ Approuver
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.user.reject', $user->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs px-3 py-1 rounded-lg"
                                                    style="background: rgba(239,68,68,0.2); color: #f87171; border: 1px solid rgba(239,68,68,0.3)">
                                                ❌ Rejeter
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Derniers utilisateurs inscrits --}}
            <div class="p-6 rounded-2xl" style="background: rgba(44,26,14,0.85); border: 1px solid rgba(244,164,41,0.2)">
                <h3 class="text-lg font-bold mb-4" style="color: #F4A429">
                    Derniers inscrits
                </h3>
                @php
                    $newUsers = \App\Models\User::latest()->take(5)->get();
                @endphp
                <div class="table-responsive">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.2)">
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Nom</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Email</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Rôle</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Inscrit le</th>
                                <th class="pb-3 text-center" style="color: rgba(244,164,41,0.7)">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newUsers as $user)
                            <tr style="border-bottom: 1px solid rgba(244,164,41,0.1)">
                                <td class="py-3 text-center font-medium" style="color: #FDF6EC">{{ $user->name }}</td>
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.7)">{{ $user->email }}</td>
                                <td class="py-3 text-center">
                                    @php
                                        $roles = [
                                            'admin'           => ['Admin',      'rgba(239,68,68,0.15)',   '#f87171', 'rgba(239,68,68,0.3)'],
                                            'vendeur_pro'     => ['Entreprise', 'rgba(74,222,128,0.15)',  '#4ade80', 'rgba(74,222,128,0.3)'],
                                            'vendeur_amateur' => ['Particulier','rgba(250,204,21,0.15)',  '#facc15', 'rgba(250,204,21,0.3)'],
                                            'acheteur'        => ['Acheteur',   'rgba(96,165,250,0.15)',  '#60a5fa', 'rgba(96,165,250,0.3)'],
                                        ];
                                        [$roleLabel, $rbg, $rc, $rbd] = $roles[$user->role] ?? [$user->role,'rgba(255,255,255,0.1)','rgba(253,246,236,0.5)','rgba(255,255,255,0.2)'];
                                    @endphp
                                    <span style="padding:0.2rem 0.6rem; border-radius:9999px; font-size:0.7rem;
                                                background:{{ $rbg }}; color:{{ $rc }}; border:1px solid {{ $rbd }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="py-3 text-center" style="color: rgba(253,246,236,0.5)">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 text-center">
                                    @if($user->role !== 'admin')
                                        <form method="POST" action="{{ route('admin.user.delete', $user->id) }}"
                                            onsubmit="return confirm('Supprimer définitivement ce compte ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs px-3 py-1 rounded-lg"
                                                    style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3)">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        #admin-stats { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; }
        #admin-pending-table, #admin-users-table, #admin-pending-accounts { overflow-x: auto; }
        #admin-pending-table table, #admin-users-table table, #admin-pending-accounts table { min-width: 500px; }
        @media (max-width: 900px) {
            #admin-stats { grid-template-columns: repeat(2, 1fr) !important; }
        }
        @media (max-width: 480px) {
            #admin-stats { grid-template-columns: repeat(2, 1fr) !important; }
        }
    </style>
</x-app-layout>