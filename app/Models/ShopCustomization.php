<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCustomization extends Model
{
    protected $fillable = ['vendor_id', 'primary_color', 'banner_image', 'bio', 'blocks', 'product_order'];

    protected $casts = [
        'blocks'        => 'array',
        'product_order' => 'array',  // ← cette ligne doit être présente
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}