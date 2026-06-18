<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Page principale Marketplace — liste toutes les boutiques (vendeurs actifs).
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->whereIn('role', ['vendeur_amateur', 'vendeur_pro'])
            ->where('status', 'actif')
            ->withCount([
                // Nombre de produits actifs par vendeur
                'products as products_count' => function ($q) {
                    $q->where('product_status', 'actif');
                },
                // Nombre de catégories distinctes par vendeur
                'products as categories_count' => function ($q) {
                    $q->where('product_status', 'actif')
                      ->distinct('category_id');
                },
            ]);

        // Filtre par type de vendeur
        if ($request->filled('type')) {
            $query->where('role', $request->type);
        }

        // Recherche par nom ou nom d'entreprise
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // On n'affiche que les vendeurs qui ont au moins 1 produit actif
        $query->having('products_count', '>', 0);

        $sellers = $query->orderByDesc('products_count')->paginate(12)->withQueryString();

        return view('products.shop', compact('sellers'));
    }

    /**
     * Boutique d'un vendeur spécifique — catégories dynamiques + produits filtrés.
     */
    public function vendor(Request $request, User $vendor)
    {
        // Vérifier que c'est bien un vendeur actif
        abort_unless(
            in_array($vendor->role, ['vendeur_amateur', 'vendeur_pro']) && $vendor->status === 'actif',
            404
        );

        // Catégories UNIQUEMENT liées aux produits actifs de ce vendeur
        $sellerCategories = Category::query()
            ->whereHas('products', function ($q) use ($vendor) {
                $q->where('user_id', $vendor->id)
                ->where('product_status', 'actif');
            })
            ->withCount([
                'products as products_count' => function ($q) use ($vendor) {
                    $q->where('user_id', $vendor->id)
                    ->where('product_status', 'actif');
                }
            ])
            ->orderByDesc('products_count')
            ->get();

        // Nombre total de produits actifs de ce vendeur
        $totalProducts = Product::where('user_id', $vendor->id)
            ->where('product_status', 'actif')
            ->count();

        // Requête produits de ce vendeur
        $productsQuery = Product::query()
            ->where('user_id', $vendor->id)
            ->where('product_status', 'actif')
            ->with(['category']);

        // Filtre par catégorie
        if ($request->filled('category')) {
            $productsQuery->where('category_id', $request->category);
        }

        // Recherche dans la boutique
        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', "%{$request->search}%");
        }

        // -------------------------------------------------------
        // ORDRE : personnalisé par le vendeur ou filtre acheteur
        // -------------------------------------------------------
        $customization = $vendor->shopCustomization;
        $rawOrder     = $customization?->product_order;
        $productOrder = is_array($rawOrder)
            ? $rawOrder
            : (json_decode((string) $rawOrder, true) ?? []);

        if (!empty($productOrder) && !$request->filled('sort')) {
            // L'acheteur n'a pas demandé de tri explicite ET
            // le vendeur a défini un ordre personnalisé → on l'applique
            $safeIds = implode(',', array_map('intval', $productOrder));
            $productsQuery->orderByRaw("FIELD(id, {$safeIds}) ASC, created_at DESC");
        } else {
            // Tri demandé par l'acheteur (ou aucun ordre personnalisé)
            match ($request->sort) {
                'price_asc'  => $productsQuery->orderBy('price', 'asc'),
                'price_desc' => $productsQuery->orderBy('price', 'desc'),
                default      => $productsQuery->latest(),
            };
        }

        $products = $productsQuery->paginate(12)->withQueryString();
        // Données d'édition pour le propriétaire
        $isOwner      = auth()->check() && auth()->id() === $vendor->id;
        $isPremium    = $isOwner && $vendor->hasPremium();
        $customization = \App\Models\ShopCustomization::firstOrCreate(
            ['vendor_id' => $vendor->id],
            ['primary_color' => '#F4A429']
        );

        return view('products.vendor-shop', [
            'seller'           => $vendor,
            'sellerCategories' => $sellerCategories,
            'products'         => $products,
            'totalProducts'    => $totalProducts,
            'isOwner'          => $isOwner,
            'isPremium'        => $isPremium,
            'customization'    => $customization,
        ]);
    }
}