<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id) {
        // Vérifie que c'est bien un acheteur
        if (auth()->check() && !auth()->user()->isAcheteur()) {
            return redirect()->route('dashboard')
                ->with('error', 'Le panier est réservé aux acheteurs.');
        }
        $product = \App\Models\Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    public function index() {
        // Vérifie que c'est bien un acheteur
        if (auth()->check() && !auth()->user()->isAcheteur()) {
            return redirect()->route('dashboard')
                ->with('error', 'Le panier est réservé aux acheteurs.');
        }
        return view('cart.index');
    }
    
    // Augmenter la quantité (+)
    public function increment($id)
    {
        // Vérifie que c'est bien un acheteur
        if (auth()->check() && !auth()->user()->isAcheteur()) {
            return redirect()->route('dashboard')
                ->with('error', 'Le panier est réservé aux acheteurs.');
        }
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    // Diminuer la quantité (-)
    public function decrement($id)
    {
        // Vérifie que c'est bien un acheteur
        if (auth()->check() && !auth()->user()->isAcheteur()) {
            return redirect()->route('dashboard')
                ->with('error', 'Le panier est réservé aux acheteurs.');
        }
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            if($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]); // Supprime si on descend en dessous de 1
            }
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    // Supprimer complètement un produit
    public function remove($id)
    {
        // Vérifie que c'est bien un acheteur
        if (auth()->check() && !auth()->user()->isAcheteur()) {
            return redirect()->route('dashboard')
                ->with('error', 'Le panier est réservé aux acheteurs.');
        }
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }
}
