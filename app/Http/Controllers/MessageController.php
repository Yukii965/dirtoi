<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Liste toutes les conversations de l'utilisateur connecté
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::with(['buyer', 'vendor', 'product', 'lastMessage'])
            ->where('buyer_id', $userId)
            ->orWhere('vendor_id', $userId)
            ->orderByDesc('updated_at')
            ->get();

        return view('messages.index', compact('conversations'));
    }

    // Affiche une conversation et marque les messages comme lus
    public function show(Conversation $conversation)
    {
        $userId = Auth::id();

        // Vérifie que l'utilisateur fait partie de cette conversation
        if ($conversation->buyer_id !== $userId && $conversation->vendor_id !== $userId) {
            abort(403);
        }

        // Marque les messages de l'interlocuteur comme lus
        $conversation->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return view('messages.show', compact('conversation', 'messages'));
    }

    // Méthode commune : trouve ou crée UNE SEULE conversation par paire acheteur/vendeur.
    // Appelée depuis la fiche produit ET depuis la page boutique du vendeur.
    // Le produit est gardé en contexte uniquement si c'est la PREMIÈRE conversation.
    private function findOrCreateConversation(int $buyerId, int $vendorId, ?int $productId = null): Conversation
    {
        // On cherche d'abord n'importe quelle conversation existante entre ces deux utilisateurs,
        // peu importe le produit — on ne veut qu'un seul fil par paire acheteur/vendeur
        $existing = Conversation::where('buyer_id', $buyerId)
            ->where('vendor_id', $vendorId)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Aucune conversation existante → on en crée une nouvelle
        // On garde le produit en contexte si on vient d'une fiche produit
        return Conversation::create([
            'buyer_id'   => $buyerId,
            'vendor_id'  => $vendorId,
            'product_id' => $productId,
        ]);
    }

    // Depuis la fiche produit
    public function startOrResume(Request $request, \App\Models\Product $product)
    {
        $buyer  = Auth::user();
        $vendor = $product->user;

        if ($buyer->id === $vendor->id) {
            return back()->with('error', 'Vous ne pouvez pas vous contacter vous-même.');
        }

        $conversation = $this->findOrCreateConversation($buyer->id, $vendor->id, $product->id);

        return redirect()->route('messages.show', $conversation);
    }

    // Depuis la page boutique du vendeur (sans produit précis)
    public function startWithVendor(Request $request, \App\Models\User $vendor)
    {
        $buyer = Auth::user();

        if ($buyer->id === $vendor->id) {
            return back()->with('error', 'Vous ne pouvez pas vous contacter vous-même.');
        }

        $conversation = $this->findOrCreateConversation($buyer->id, $vendor->id, null);

        return redirect()->route('messages.show', $conversation);
    }
    // Envoie un message dans une conversation
    public function send(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();

        if ($conversation->buyer_id !== $userId && $conversation->vendor_id !== $userId) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $userId,
            'body'            => $request->body,
        ]);

        // Met à jour updated_at de la conversation pour le tri
        $conversation->touch();

        return back();
    }
    // Supprime une conversation (accessible à l'acheteur ET au vendeur)
    public function destroy(Conversation $conversation)
    {
        $userId = Auth::id();

        // Vérifie que l'utilisateur fait partie de cette conversation
        if ($conversation->buyer_id !== $userId && $conversation->vendor_id !== $userId) {
            abort(403);
        }

        // Supprime la conversation (les messages sont supprimés en cascade grâce au onDelete('cascade'))
        $conversation->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Conversation supprimée.');
    }
}