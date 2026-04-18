<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class NavigationController extends Controller
{
    public function help() {
        return view('pages.help'); // Créer une vue pour le support
    }

    public function sell()
    {
        $categories = Category::all();
        return view('pages.sell', compact('categories'));
    }

    public function wishlist() {
        // Ici on récupèrerait les favoris de l'utilisateur en BDD
        return view('pages.wishlist');
    }
}