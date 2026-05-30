<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorController;

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
// Page d'accueil
Route::get('/', [ProductController::class, 'home'])->name('home');

// Page boutique complète
Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('products.show');
// Gestion du panier
Route::post('/cart/increment/{id}', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/decrement/{id}', [CartController::class, 'decrement'])->name('cart.decrement');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Routes pour la barre de navigation secondaire
Route::get('/meilleures-ventes', [ProductController::class, 'bestsellers'])->name('nav.bestsellers');
Route::get('/offres-du-jour', [ProductController::class, 'deals'])->name('nav.deals');
// Routes de paiement et système Escrow
// Toutes ces routes nécessitent d'être connecté
Route::middleware(['auth'])->group(function () {

    // Route admin — valider un produit en attente
    Route::post('/admin/product/{product}/approve', function(\App\Models\Product $product) {
        // Vérifie que c'est bien un admin
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        // Change le statut du produit à "actif" — visible par les acheteurs
        $product->update(['product_status' => 'actif']);
        return back()->with('success', 'Produit "' . $product->name . '" validé et maintenant visible !');
    })->name('admin.product.approve');

    // Upload photo de profil
    Route::patch('/profile/avatar', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        // Sauvegarder la nouvelle photo
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Photo de profil mise à jour !');
    })->name('profile.avatar');

    // Page de paiement
    Route::get('/checkout', [OrderController::class, 'index'])->name('checkout.index');

    // Traitement de la commande (création + blocage de l'argent)
    Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');

    // L'acheteur confirme qu'il a reçu son colis → génère le code pour le livreur
    Route::post('/orders/{order}/confirm-reception', [OrderController::class, 'confirmReception'])
        ->name('order.confirm');

    // Le livreur entre le code → argent débloqué pour le vendeur
    Route::post('/orders/{order}/validate-code', [OrderController::class, 'validateDeliveryCode'])
        ->name('order.validate-code');
    // Approuver ou rejeter un compte utilisateur
    Route::post('/admin/user/{user}/approve', function(\App\Models\User $user) {
        if (!auth()->user()->isAdmin()) abort(403);
        $user->update(['status' => 'actif']);
        return back()->with('success', 'Compte de ' . $user->name . ' approuvé !');
    })->name('admin.user.approve');

    Route::post('/admin/user/{user}/reject', function(\App\Models\User $user) {
        if (!auth()->user()->isAdmin()) abort(403);
        $user->delete();
        return back()->with('success', 'Compte rejeté et supprimé.');
    })->name('admin.user.reject');
});
// routes pour vendres
Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [VendorController::class, 'create'])->name('vendor.sell');
    Route::post('/sell', [VendorController::class, 'store'])->name('vendor.publish');
});

Route::controller(NavigationController::class)->group(function () {
    Route::get('/service-client', 'help')->name('nav.help');
    Route::get('/vendre', 'sell')->name('nav.sell');
});

// Route protégée (nécessite d'être connecté)
Route::get('/favoris', [NavigationController::class, 'wishlist'])
    ->middleware(['auth'])
    ->name('nav.wishlist');

// Route du dashboard — redirige vers le bon tableau de bord selon le rôle
Route::get('/dashboard', function () {

    $user = auth()->user();

    // Si c'est un admin → tableau de bord admin
    if ($user->isAdmin()) {
        return view('admin.dashboard');
    }

    // Si c'est un vendeur (amateur ou pro) → tableau de bord vendeur
    if ($user->isVendeur()) {
        return view('vendor.dashboard');
    }

    // Sinon c'est un acheteur → tableau de bord acheteur
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tracking', function (Illuminate\Http\Request $request) {
    $address = $request->query('address', 'Antananarivo, Madagascar');
    return view('pages.tracking', compact('address'));
})->name('order.tracking');

require __DIR__.'/auth.php';

// Page d'attente après inscription
Route::get('/pending', function () {
    return view('auth.pending');
})->name('auth.pending');