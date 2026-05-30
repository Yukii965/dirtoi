<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ShopController;

// -------------------------------------------------------
// MARKETPLACE
// -------------------------------------------------------

// Page principale : liste des vendeurs
Route::get('/boutique', [ShopController::class, 'index'])->name('products.index');

// Boutique d'un vendeur spécifique
Route::get('/boutique/vendeur/{vendor}', [ShopController::class, 'vendor'])->name('shop.vendor');

// -------------------------------------------------------
// PRODUITS
// -------------------------------------------------------

// Page d'accueil
Route::get('/', [ProductController::class, 'home'])->name('home');

// Fiche produit individuelle  ← /shop remplacé par /boutique
Route::get('/boutique/produit/{slug}', [ProductController::class, 'show'])->name('products.show');

// Barre de navigation secondaire
Route::get('/meilleures-ventes', [ProductController::class, 'bestsellers'])->name('nav.bestsellers');
Route::get('/offres-du-jour', [ProductController::class, 'deals'])->name('nav.deals');

// -------------------------------------------------------
// PANIER
// -------------------------------------------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/increment/{id}', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/decrement/{id}', [CartController::class, 'decrement'])->name('cart.decrement');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// -------------------------------------------------------
// ROUTES AUTHENTIFIÉES
// -------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Admin — valider un produit en attente
    Route::post('/admin/product/{product}/approve', function (\App\Models\Product $product) {
        if (!auth()->user()->isAdmin()) abort(403);
        $product->update(['product_status' => 'actif']);
        return back()->with('success', 'Produit "' . $product->name . '" validé et maintenant visible !');
    })->name('admin.product.approve');

    // Upload photo de profil
    Route::patch('/profile/avatar', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $user = auth()->user();
        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        return back()->with('success', 'Photo de profil mise à jour !');
    })->name('profile.avatar');

    // Checkout & Escrow
    Route::get('/checkout', [OrderController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
    Route::post('/orders/{order}/confirm-reception', [OrderController::class, 'confirmReception'])->name('order.confirm');
    Route::post('/orders/{order}/validate-code', [OrderController::class, 'validateDeliveryCode'])->name('order.validate-code');

    // Admin — approuver/rejeter un compte utilisateur
    Route::post('/admin/user/{user}/approve', function (\App\Models\User $user) {
        if (!auth()->user()->isAdmin()) abort(403);
        $user->update(['status' => 'actif']);
        return back()->with('success', 'Compte de ' . $user->name . ' approuvé !');
    })->name('admin.user.approve');

    Route::post('/admin/user/{user}/reject', function (\App\Models\User $user) {
        if (!auth()->user()->isAdmin()) abort(403);
        $user->delete();
        return back()->with('success', 'Compte rejeté et supprimé.');
    })->name('admin.user.reject');

    // Vendre
    Route::get('/sell', [VendorController::class, 'create'])->name('vendor.sell');
    Route::post('/sell', [VendorController::class, 'store'])->name('vendor.publish');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------------------------------------------
// NAVIGATION & PAGES
// -------------------------------------------------------
Route::controller(NavigationController::class)->group(function () {
    Route::get('/service-client', 'help')->name('nav.help');
    Route::get('/vendre', 'sell')->name('nav.sell');
});

Route::get('/favoris', [NavigationController::class, 'wishlist'])
    ->middleware(['auth'])
    ->name('nav.wishlist');

// Dashboard — redirige selon le rôle
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin())   return view('admin.dashboard');
    if ($user->isVendeur()) return view('vendor.dashboard');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Tracking
Route::get('/tracking', function (\Illuminate\Http\Request $request) {
    $address = $request->query('address', 'Antananarivo, Madagascar');
    return view('pages.tracking', compact('address'));
})->name('order.tracking');

// Page d'attente après inscription
Route::get('/pending', function () {
    return view('auth.pending');
})->name('auth.pending');

require __DIR__ . '/auth.php';