<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On agrandit le champ price dans products
        // decimal(8,2) → maximum 999 999.99 Ar — trop petit pour Madagascar !
        // decimal(15,2) → maximum 9 999 999 999 999.99 Ar — largement suffisant
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
        });

        // On corrige aussi les montants dans orders
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->change();
            $table->decimal('commission_amount', 15, 2)->change();
            $table->decimal('seller_amount', 15, 2)->change();
        });

        // Et dans commissions
        Schema::table('commissions', function (Blueprint $table) {
            $table->decimal('sale_amount', 15, 2)->change();
            $table->decimal('commission_amount', 15, 2)->change();
            $table->decimal('seller_received', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->change();
        });
    }
};