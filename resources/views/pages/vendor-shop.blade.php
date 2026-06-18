<x-app-layout>
    <div class="py-12">
        <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem">

            {{-- En-tête boutique --}}
            <div class="p-8 rounded-2xl mb-8" style="background:rgba(44,26,14,0.85);border:1px solid rgba(244,164,41,0.2)">
                <div id="vendor-shop-header" style="display:flex;align-items:center;gap:1.5rem">
                    @if($vendeur->avatar)
                        <img src="{{ asset('storage/'.$vendeur->avatar) }}"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #F4A429">
                    @else
                        <div style="width:80px;height:80px;border-radius:50%;background:#F4A429;color:#2C1A0E;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:900">
                            {{ substr($vendeur->name,0,1) }}
                        </div>
                    @endif
                    <div>
                        <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:900;color:#FDF6EC">
                            {{ $vendeur->company_name ?? $vendeur->name }}
                        </h1>
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-top:0.5rem">
                            @if($vendeur->isVendeurPro())
                                <span style="background:rgba(34,197,94,0.2);color:#4ade80;font-size:0.75rem;padding:0.25rem 0.75rem;border-radius:999px;font-weight:700">
                                    🏢 Entreprise vérifiée
                                </span>
                            @else
                                <span style="background:rgba(244,164,41,0.15);color:#F4A429;font-size:0.75rem;padding:0.25rem 0.75rem;border-radius:999px;font-weight:700">
                                    👤 Vendeur particulier
                                </span>
                            @endif
                            <span style="color:rgba(253,246,236,0.5);font-size:0.875rem">
                                {{ $produits->count() }} produit(s)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Produits --}}
            @if($produits->isEmpty())
                <div class="text-center py-20 rounded-2xl" style="background:rgba(44,26,14,0.5);border:2px dashed rgba(244,164,41,0.2)">
                    <p style="color:rgba(253,246,236,0.5)">Ce vendeur n'a pas encore de produits.</p>
                </div>
            @else
                <div id="vendor-products-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px,1fr)); gap:1.25rem;">
                    @foreach($produits as $produit)
                        <a href="{{ route('products.show', $produit->slug) }}" class="gasy-card block" style="text-decoration:none;overflow:hidden">
                            <div style="height:200px;overflow:hidden">
                                <img src="{{ asset('storage/'.$produit->image) }}"
                                     style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s"
                                     onmouseover="this.style.transform='scale(1.1)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            </div>
                            <div style="padding:1rem">
                                <span style="font-size:0.7rem;background:rgba(244,164,41,0.15);color:#F4A429;padding:0.2rem 0.6rem;border-radius:999px">
                                    {{ $produit->category->name }}
                                </span>
                                <p style="font-weight:700;color:#FDF6EC;margin-top:0.5rem">{{ $produit->name }}</p>
                                <p style="font-size:1.25rem;font-weight:900;color:#F4A429;margin-top:0.25rem">
                                    {{ number_format($produit->price,0,',',' ') }} Ar
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <style>
        #vendor-shop-header { flex-wrap: wrap !important; }
        #vendor-products-grid { display: grid !important; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)) !important; }
        @media (max-width: 480px) {
            #vendor-products-grid { grid-template-columns: repeat(2, 1fr) !important; }
            #vendor-shop-header img, #vendor-shop-header > div:first-child { width: 60px !important; height: 60px !important; }
        }
    </style>
</x-app-layout>