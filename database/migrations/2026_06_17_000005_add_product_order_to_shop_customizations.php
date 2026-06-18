<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shop_customizations', function (Blueprint $table) {
            // Tableau JSON d'IDs de produits dans l'ordre choisi par le vendeur
            $table->longText('product_order')->nullable()->after('blocks');
        });
    }
    public function down(): void
    {
        Schema::table('shop_customizations', function (Blueprint $table) {
            $table->dropColumn('product_order');
        });
    }
};