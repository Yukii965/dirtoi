<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
        'delivery_code',
        'confirmation_deadline',
        'commission_amount',
        'seller_amount',
        'mvola_transaction_id',
    ];

    protected $casts = [
        // Convertit automatiquement en objet Carbon pour manipuler les dates
        'confirmation_deadline' => 'datetime',
    ];

    // -------------------------------------------------------
    // RELATIONS
    // -------------------------------------------------------

    // La commande appartient à un acheteur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une commande a plusieurs articles
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // -------------------------------------------------------
    // MÉTHODES UTILES
    // -------------------------------------------------------

    // Génère un code de livraison aléatoire sécurisé
    // Exemple de code généré : "GM-A3F9-X7K2"
    public static function generateDeliveryCode(): string
    {
        return 'GM-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
    }

    // Calcule le montant de la commission selon le type de vendeur
    // vendeur_amateur → 5% | vendeur_pro → 0% (il paie un abonnement)
    public static function calculateCommission(float $amount, string $sellerRole): array
    {
        $rate = $sellerRole === 'vendeur_amateur' ? 5 : 0;
        $commission = $amount * ($rate / 100);
        $sellerAmount = $amount - $commission;

        return [
            'rate'          => $rate,
            'commission'    => $commission,
            'seller_amount' => $sellerAmount,
        ];
    }

    // Vérifie si la deadline de confirmation est dépassée
    public function isDeadlinePassed(): bool
    {
        return $this->confirmation_deadline &&
               now()->isAfter($this->confirmation_deadline);
    }
}