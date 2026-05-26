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
        $categories = \App\Models\Category::all(); 

        return view('pages.sell', compact('categories'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);

        // Upload de l'image dans storage/app/public/products
        $path = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(100, 999),
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $path,
            'stock' => 1, // Par défaut
        ]);

        return redirect()->route('home')->with('success', 'Votre produit est maintenant en ligne sur le réseau DirToi !');
    }
}