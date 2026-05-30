<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function create()
    {
        // Vérifie que l'utilisateur connecté est bien un vendeur
        if (!Auth::check() || !Auth::user()->isVendeur()) {
            return redirect()->route('home')
                ->with('error', 'Accès réservé aux vendeurs.');
        }

        $categories = Category::all();
        return view('pages.sell', compact('categories'));
    }

    public function store(Request $request)
    {
        // Vérifie que l'utilisateur connecté est bien un vendeur
        if (!Auth::check() || !Auth::user()->isVendeur()) {
            return redirect()->route('home')
                ->with('error', 'Accès réservé aux vendeurs.');
        }

        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'required',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        // Upload de l'image
        $path = $request->file('image')->store('products', 'public');

        // Création du produit avec tous les champs
        Product::create([
            'name'           => $request->name,
            'slug'           => Str::slug($request->name) . '-' . rand(100, 999),
            'description'    => $request->description,
            'price'          => $request->price,
            'category_id'    => $request->category_id,
            'image'          => $path,
            'stock' => $request->stock,

            // Le vendeur propriétaire du produit
            'user_id'        => Auth::id(),

            // En attente de validation par un admin
            'product_status' => 'en_attente',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Produit soumis ! Il sera visible après validation.');
    }
}