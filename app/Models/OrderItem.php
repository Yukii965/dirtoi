<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    // Un article appartient à une commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Un article est lié à un produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Calcule le sous-total de cet article
    public function subtotal(): float
    {
        return $this->quantity * $this->unit_price;
    }
}