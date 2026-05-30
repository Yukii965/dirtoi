<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'order_id',
        'seller_id',
        'sale_amount',
        'commission_rate',
        'commission_amount',
        'seller_received',
    ];

    // Une commission appartient à une commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Une commission appartient à un vendeur
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}