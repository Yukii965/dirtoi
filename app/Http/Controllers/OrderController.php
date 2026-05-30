<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Affiche la page de paiement (checkout)
    public function index()
    {
        // Vérifie si le panier n'est pas vide
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('home')
                ->with('error', 'Votre panier est vide.');
        }

        // Calcule le total du panier
        $total = 0;
        foreach (session('cart') as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('pages.checkout', compact('total'));
    }

    // Traite la commande — c'est le cœur du système Escrow
    public function process(Request $request)
    {
        $request->validate([
            'address'        => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'mvola_number'   => 'required|string|max:20',
        ]);

        // On utilise une transaction DB pour que tout soit annulé si une erreur survient
        // Exemple : si la commande est créée mais que les articles échouent → tout est annulé
        DB::transaction(function () use ($request) {

            $cart  = session('cart');
            $total = 0;

            // Calcule le total du panier
            foreach ($cart as $details) {
                $total += $details['price'] * $details['quantity'];
            }

            // Crée la commande principale avec statut "paye_retenu"
            // L'argent est considéré retenu dès que la commande est créée
            // (dans la vraie intégration MVola, ce statut sera mis après confirmation API)
            $order = Order::create([
                'user_id'                 => Auth::id(),
                'product_id'              => array_key_first($cart), // produit principal
                'quantity'                => count($cart),
                'total_price'             => $total,
                'status'                  => 'paye_retenu',

                // Deadline : l'acheteur a 48h pour confirmer après livraison
                'confirmation_deadline'   => now()->addHours(48),

                // On met 0 pour la commission pour l'instant
                // Elle sera calculée vendeur par vendeur quand on aura l'info
                'commission_amount'       => 0,
                'seller_amount'           => $total,

                // Numéro MVola de l'acheteur pour traçabilité
                'mvola_transaction_id'    => 'MVola-' . $request->mvola_number . '-' . time(),
            ]);

            // Crée les articles de la commande (un par produit dans le panier)
            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $details['quantity'],
                    'unit_price' => $details['price'],
                ]);

                // Diminue le stock du produit
                Product::where('id', $productId)
                    ->decrement('stock', $details['quantity']);
            }

            // Vide le panier après création de la commande
            session()->forget('cart');
        });

        return redirect()->route('dashboard')
            ->with('success', 'Commande confirmée ! Votre argent est sécurisé jusqu\'à la livraison.');
    }

    // L'acheteur confirme qu'il a bien reçu son colis
    public function confirmReception(Request $request, Order $order)
    {
        // Vérifie que c'est bien l'acheteur de cette commande
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Vérifie que la commande est bien en cours de livraison
        if ($order->status !== 'livraison_en_cours') {
            return back()->with('error', 'Cette commande ne peut pas être confirmée.');
        }

        // Génère le code de livraison et l'envoie au livreur
        $deliveryCode = Order::generateDeliveryCode();

        $order->update([
            'status'        => 'confirme_acheteur',
            'delivery_code' => $deliveryCode,
        ]);

        // TODO : Envoyer le code au livreur par SMS ou notification
        // Pour l'instant on l'affiche dans le dashboard

        return back()->with('success', 'Réception confirmée ! Code envoyé au livreur : ' . $deliveryCode);
    }

    // Le livreur entre le code pour finaliser la livraison
    public function validateDeliveryCode(Request $request, Order $order)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Vérifie que le code est correct
        if ($order->delivery_code !== $request->code) {
            return back()->with('error', 'Code incorrect. Veuillez réessayer.');
        }

        // Vérifie que la commande attend bien le code
        if ($order->status !== 'confirme_acheteur') {
            return back()->with('error', 'Cette commande n\'attend pas de code.');
        }

        // Récupère le vendeur du premier produit de la commande
        $firstItem   = $order->items()->with('product.user')->first();
        $seller      = $firstItem?->product?->user;

        if ($seller) {
            // Calcule la commission selon le rôle du vendeur
            $commission = Order::calculateCommission($order->total_price, $seller->role);

            // Met à jour la commande avec les montants finaux
            $order->update([
                'status'            => 'termine',
                'commission_amount' => $commission['commission'],
                'seller_amount'     => $commission['seller_amount'],
            ]);

            // Enregistre la commission dans la table commissions
            // Utile pour la comptabilité de votre ami en finance
            \App\Models\Commission::create([
                'order_id'         => $order->id,
                'seller_id'        => $seller->id,
                'sale_amount'      => $order->total_price,
                'commission_rate'  => $commission['rate'],
                'commission_amount'=> $commission['commission'],
                'seller_received'  => $commission['seller_amount'],
            ]);

            // TODO : Déclencher le virement MVola vers le vendeur
            // $this->sendMvolaPayment($seller->mvola_number, $commission['seller_amount']);
        }

        return back()->with('success', 'Livraison validée ! Le vendeur va recevoir son paiement.');
    }
}