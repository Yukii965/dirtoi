<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Page d'accueil — produits en vedette
    public function home(Request $request)
    {
        $query = Product::with(['category', 'user'])
                        ->where('product_status', 'actif');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $products  = $query->latest()->paginate(8);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
                          ->where('product_status', 'actif')
                          ->with(['category', 'user'])
                          ->firstOrFail();

        $similarProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('product_status', 'actif')
                                  ->limit(4)
                                  ->get();

        return view('products.show', compact('product', 'similarProducts'));
    }

    public function filterByCategory(string $categorySlug)
    {
        $category   = Category::where('slug', $categorySlug)->firstOrFail();
        $products   = Product::where('category_id', $category->id)
                              ->where('product_status', 'actif')
                              ->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories', 'category'));
    }

    public function deals()
    {
        $products = Product::where('price', '<', 1000)
                           ->where('product_status', 'actif')
                           ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function bestsellers()
    {
        $products = Product::orderBy('stock', 'asc')
                           ->where('product_status', 'actif')
                           ->paginate(12);

        return view('products.index', compact('products'));
    }
}