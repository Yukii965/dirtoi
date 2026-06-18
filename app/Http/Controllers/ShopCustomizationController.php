<?php
namespace App\Http\Controllers;

use App\Models\ShopCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopCustomizationController extends Controller
{
    // Page de personnalisation de la boutique
    public function edit()
    {
        $vendor = Auth::user();
        $isPremium = $vendor->hasPremium();

        // Crée une config par défaut si elle n'existe pas encore
        $customization = ShopCustomization::firstOrCreate(
            ['vendor_id' => $vendor->id],
            ['primary_color' => '#F4A429', 'bio' => null, 'blocks' => []]
        );

        return view('vendor.shop-customize', compact('vendor', 'customization', 'isPremium'));
    }

    // Sauvegarde les paramètres de base (couleur, bio)
    public function saveBase(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'bio'           => 'nullable|string|max:500',
        ]);

        $vendor        = Auth::user();
        $customization = ShopCustomization::firstOrCreate(['vendor_id' => $vendor->id]);
        $customization->update([
            'primary_color' => $request->primary_color,
            'bio'           => $request->bio,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', '✅ Sauvegardé !');
    }

    // Upload de la bannière
    public function saveBanner(Request $request)
    {
        $request->validate(['banner' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072']);

        $vendor = Auth::user();
        $customization = ShopCustomization::firstOrCreate(['vendor_id' => $vendor->id]);

        // Supprime l'ancienne bannière si elle existe
        if ($customization->banner_image) {
            Storage::disk('public')->delete($customization->banner_image);
        }

        $path = $request->file('banner')->store('shop-banners', 'public');
        $customization->update(['banner_image' => $path]);

        return back()->with('success', '✅ Bannière mise à jour !');
    }

    // Sauvegarde les blocs premium (JSON)
    public function saveBlocks(Request $request)
    {
        $vendor = Auth::user();

        // Double vérification côté serveur
        if (!$vendor->hasPremium()) {
            return back()->with('error', '🔒 Fonctionnalité réservée aux abonnés Premium.');
        }

        $request->validate(['blocks' => 'nullable|string']);

        $blocks = json_decode($request->blocks, true) ?? [];

        // Limite à 8 blocs maximum
        $blocks = array_slice($blocks, 0, 8);

        $customization = ShopCustomization::firstOrCreate(['vendor_id' => $vendor->id]);
        $customization->update(['blocks' => $blocks]);

        return back()->with('success', '✅ Blocs sauvegardés !');
    }

    // Upload d'image pour un bloc premium
    public function uploadBlockImage(Request $request)
    {
        $vendor = Auth::user();
        if (!$vendor->hasPremium()) abort(403);

        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072']);
        $path = $request->file('image')->store('shop-blocks', 'public');

        return response()->json(['path' => $path, 'url' => Storage::url($path)]);
    }
    // Sauvegarde l'ordre personnalisé des produits
    public function saveProductOrder(Request $request)
    {
        $vendor = Auth::user();
        if (!$vendor) return response()->json(['error' => 'Non authentifié'], 401);

        $order   = json_decode($request->order, true) ?? [];
        $validIds = $vendor->products()->pluck('id')->toArray();
        $order   = array_values(array_filter($order, fn($id) => in_array($id, $validIds)));

        $customization = ShopCustomization::firstOrCreate(['vendor_id' => $vendor->id]);
        $customization->update(['product_order' => $order]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', '✅ Ordre sauvegardé !');
    }
}