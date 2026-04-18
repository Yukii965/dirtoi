<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id) {
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
        return view('cart.index');
    }
    
    // Augmenter la quantité (+)
    public function increment($id)
    {
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
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }
}
