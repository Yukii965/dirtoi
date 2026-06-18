<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ShopCustomizationController;

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

    // Personnalisation de boutique vendeur
    Route::get('/ma-boutique', function () {
        return redirect()->route('shop.vendor', auth()->user()->id);
    })->name('vendor.customize')->middleware('auth');
    Route::post('/ma-boutique/base',              [ShopCustomizationController::class, 'saveBase'])->name('vendor.customize.base');
    Route::post('/ma-boutique/banniere',          [ShopCustomizationController::class, 'saveBanner'])->name('vendor.customize.banner');
    Route::post('/ma-boutique/blocs',             [ShopCustomizationController::class, 'saveBlocks'])->name('vendor.customize.blocks');
    Route::post('/ma-boutique/bloc-image',        [ShopCustomizationController::class, 'uploadBlockImage'])->name('vendor.customize.block-image');
    Route::post('/ma-boutique/ordre-produits', [ShopCustomizationController::class, 'saveProductOrder'])->name('vendor.customize.product-order');

    // Nouvelles actions vendeur sur les commandes
    Route::post('/orders/{order}/accept',         [OrderController::class, 'acceptOrder'])->name('order.accept');
    Route::post('/orders/{order}/start-delivery', [OrderController::class, 'startDelivery'])->name('order.start-delivery');

    // Messagerie acheteur ↔ vendeur
    Route::get('/messages',                              [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}',               [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}/send',         [MessageController::class, 'send'])->name('messages.send');
    Route::post('/messages/start/{product}',             [MessageController::class, 'startOrResume'])->name('messages.start');
    Route::post('/messages/contact-vendor/{vendor}', [MessageController::class, 'startWithVendor'])->name('messages.start-vendor');
    Route::delete('/messages/{conversation}/destroy', [MessageController::class, 'destroy'])->name('messages.destroy');
    
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

// Nouvelle inscription multi-étapes
Route::get('/register', function() {
    return view('auth.register-choice');
})->name('register')->middleware('guest');

Route::get('/register/{role}', function($role) {
    if (!in_array($role, ['acheteur', 'vendeur_amateur', 'vendeur_pro'])) {
        return redirect()->route('register');
    }
    return view('auth.register-form', compact('role'));
})->name('register.form')->middleware('guest');

// Page caméra détection faciale
Route::get('/register/camera', function() {
    // Vérifie que l'utilisateur vient bien du formulaire
    if (!session()->has('pending_registration')) {
        return redirect()->route('register');
    }
    return view('auth.camera');
})->name('register.camera')->middleware('guest');

// Traitement de la photo capturée
Route::post('/register/photo', function(\Illuminate\Http\Request $request) {
    $photoData = $request->photo_data;

    // Convertir base64 en fichier
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photoData));
    $fileName = 'avatars/' . uniqid() . '.jpg';
    \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageData);

    // Récupérer les données du formulaire en session
    $registration = session('pending_registration');
    $registration['avatar'] = $fileName;

    // Créer le compte maintenant
    $user = \App\Models\User::create([
        'name'         => $registration['name'],
        'email'        => $registration['email'],
        'password'     => $registration['password'],
        'role'         => $registration['role'],
        'mvola_number' => $registration['mvola_number'] ?? null,
        'company_name' => $registration['company_name'] ?? null,
        'status'       => 'en_attente',
        'avatar'       => $fileName,
    ]);

    session()->forget('pending_registration');

    return redirect()->route('auth.pending')
        ->with('pending_name', $user->name)
        ->with('pending_email', $user->email);
})->name('register.photo')->middleware('guest');

// Admin — suspendre un produit
Route::post('/admin/product/{product}/suspend', function(\App\Models\Product $product) {
    if (!auth()->user()->isAdmin()) abort(403);
    $product->update(['product_status' => 'suspendu']);
    return back()->with('success', 'Produit suspendu.');
})->name('admin.product.suspend')->middleware('auth');

// Vendeur — supprimer son produit
Route::delete('/vendor/product/{product}', function(\App\Models\Product $product) {
    if (auth()->id() !== $product->user_id) abort(403);
    $product->delete();
    return redirect()->route('dashboard')->with('success', 'Produit supprimé.');
})->name('vendor.product.delete')->middleware('auth');

// Vendeur — modifier son produit
Route::get('/vendor/product/{product}/edit', function(\App\Models\Product $product) {
    if (auth()->id() !== $product->user_id) abort(403);
    $categories = \App\Models\Category::all();
    return view('vendor.edit-product', compact('product', 'categories'));
})->name('vendor.product.edit')->middleware('auth');

// Admin — supprimer un utilisateur
Route::delete('/admin/user/{user}', function(\App\Models\User $user) {
    if (!auth()->user()->isAdmin()) abort(403);
    if ($user->role === 'admin') abort(403);
    $user->delete();
    return back()->with('success', 'Compte supprimé.');
})->name('admin.user.delete')->middleware('auth');

Route::patch('/vendor/product/{product}', function(\Illuminate\Http\Request $request, \App\Models\Product $product) {
    if (auth()->id() !== $product->user_id) abort(403);
    $request->validate(['name'=>'required','price'=>'required|numeric','stock'=>'required|integer','description'=>'required','category_id'=>'required']);
    $data = $request->only(['name','price','stock','description','category_id']);
    $data['slug'] = \Illuminate\Support\Str::slug($request->name).'-'.rand(100,999);
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products','public');
    }
    $product->update($data);
    return redirect()->route('dashboard')->with('success','Produit modifié !');
})->name('vendor.product.update')->middleware('auth');

Route::get('/politique', function() { return view('pages.policy'); })->name('policy');
Route::get('/conditions', function() { return view('pages.terms'); })->name('terms');

Route::get('/vendeur/{id}', function($id) {
    $vendeur = \App\Models\User::findOrFail($id);
    $produits = \App\Models\Product::where('user_id', $id)
                ->where('product_status', 'actif')
                ->with('category')->latest()->get();
    return view('pages.vendor-shop', compact('vendeur', 'produits'));
})->name('vendor.shop');

