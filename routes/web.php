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
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('products.show');
// Gestion du panier
Route::post('/cart/increment/{id}', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/decrement/{id}', [CartController::class, 'decrement'])->name('cart.decrement');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Routes pour la barre de navigation secondaire
Route::get('/meilleures-ventes', [ProductController::class, 'bestsellers'])->name('nav.bestsellers');
Route::get('/offres-du-jour', [ProductController::class, 'deals'])->name('nav.deals');
// Gestion payement
Route::get('/checkout', [OrderController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
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

Route::get('/dashboard', function () {
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