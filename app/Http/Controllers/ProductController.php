<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche la liste de tous les produits (La Boutique)
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // On vérifie si une catégorie est demandée dans l'URL
        if ($request->has('category') && $request->category != null) {
            $category = Category::where('slug', $request->category)->first();
            if($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->latest()->paginate(12);
        
        // IMPORTANT : On renvoie TOUJOURS les catégories pour le menu "Toutes"
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Affiche les détails d'un produit spécifique
     */
    public function show(string $slug)
    {
        // On cherche le produit par son "slug" (ex: iphone-15-pro) au lieu de l'ID
        // C'est bien meilleur pour le référencement Google (SEO)
        $product = Product::where('slug', $slug)->firstOrFail();

        // Bonus : On récupère 4 produits similaires de la même catégorie
        $similarProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $product->id)
                                    ->limit(4)
                                    ->get();

        return view('products.show', compact('product', 'similarProducts'));
    }

    /**
     * Filtrer les produits par catégorie
     */
    public function filterByCategory(string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        $products = Product::where('category_id', $category->id)
                            ->paginate(12);
                            
        $categories = Category::all();

        return view('products.index', compact('products', 'categories', 'category'));
    }
    public function deals()
    {
        // On simule des promos sur les produits de moins de 500 Ar
        $products = Product::where('price', '<', 1000)->paginate(12);
        return view('products.index', compact('products'));
    }

    public function bestsellers()
    {
        // On simule les meilleures ventes avec le stock le plus bas
        $products = Product::orderBy('stock', 'asc')->paginate(12);
        return view('products.index', compact('products'));
    }
}