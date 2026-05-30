<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'category_id',
        'stock',

        // Champs ajoutés pour GasyMarket
        // Sans ces deux lignes Laravel refusait de les sauvegarder !
        'user_id',
        'product_status',
    ];

    // Un produit appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Un produit appartient à un vendeur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}