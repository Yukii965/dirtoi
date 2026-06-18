<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tous les champs qu'on autorise à remplir lors de la création
    // ou modification d'un utilisateur
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mvola_number',
        'company_name',
        'status',
        'avatar',
    ];

    // Champs cachés — jamais envoyés au navigateur
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // -------------------------------------------------------
    // MÉTHODES UTILES pour vérifier le rôle de l'utilisateur
    // Exemple d'utilisation : if(auth()->user()->isAdmin()) ...
    // -------------------------------------------------------

    // Vérifie si l'utilisateur est un admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Vérifie si l'utilisateur est un acheteur
    public function isAcheteur(): bool
    {
        return $this->role === 'acheteur';
    }

    // Vérifie si l'utilisateur est un vendeur amateur (particulier)
    public function isVendeurAmateur(): bool
    {
        return $this->role === 'vendeur_amateur';
    }

    // Vérifie si l'utilisateur est un vendeur pro (entreprise)
    public function isVendeurPro(): bool
    {
        return $this->role === 'vendeur_pro';
    }

    // Vérifie si l'utilisateur est un vendeur (amateur OU pro)
    public function isVendeur(): bool
    {
        return in_array($this->role, ['vendeur_amateur', 'vendeur_pro']);
    }

    // -------------------------------------------------------
    // RELATIONS avec les autres tables
    // -------------------------------------------------------

    // Un utilisateur peut avoir plusieurs commandes (en tant qu'acheteur)
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    // Un vendeur peut avoir plusieurs produits
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Un vendeur pro peut avoir plusieurs abonnements
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // -------------------------------------------------------
    // CAST — convertit automatiquement certains champs
    // -------------------------------------------------------
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relation vers la personnalisation de boutique
    public function shopCustomization()
    {
        return $this->hasOne(\App\Models\ShopCustomization::class, 'vendor_id');
    }

    // Vérifie si le vendeur a un abonnement premium actif
    public function hasPremium(): bool
    {
        return $this->subscriptions()
            ->where('status', 'actif')
            ->where('end_date', '>=', today())
            ->exists();
    }
}