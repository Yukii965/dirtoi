<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed; // N'oublie pas cette ligne !
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Vérifie si le panier n'est pas vide avant d'aller au paiement
        if(!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('home')->with('error', 'Votre panier est vide.');
        }
        return view('pages.checkout');
    }

    public function process(Request $request)
    {
        // 1. Validation des données du formulaire
        $request->validate([
            'payment_method' => 'required',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
        ]);

        // 2. Préparation des détails pour l'email
        $details = [
            'payment_method' => $request->payment_method,
            'address' => $request->address,
            'phone' => $request->phone,
            'user_name' => Auth::user()->name,
        ];

        // 3. Envoi de l'email de confirmation
        // On utilise l'email de l'utilisateur actuellement connecté
        try {
            Mail::to(Auth::user()->email)->send(new OrderConfirmed($details));
        } catch (\Exception $e) {
            // Si l'email échoue (ex: mauvaise config .env), on continue quand même la commande
            // mais on peut logger l'erreur si besoin
        }

        // 4. Logique selon le mode de paiement
        $method = $request->payment_method;

        if ($method === 'delivery') {
            // On vide le panier après le succès
            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Transaction confirmée ! Un email de suivi a été envoyé à votre terminal.');
        }
        $address = $request->address;

        return redirect()->route('order.tracking', ['address' => $address])
                        ->with('success', 'Transaction confirmée ! Suivez votre colis en temps réel.');

        // Pour PayPal ou Mobile Money (Simulation)
        // Normalement ici on ne vide pas le panier tant que le paiement n'est pas confirmé par l'API
        return back()->with('success', "Connexion au portail $method établie. Vérifiez votre boîte mail.");
        
    }
}