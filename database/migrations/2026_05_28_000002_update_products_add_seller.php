<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Lien entre le produit et son vendeur
            // Grâce à ceci on peut savoir : "ce produit appartient à quel vendeur ?"
            // constrained() = vérifie que le user_id existe bien dans la table users
            // onDelete('cascade') = si le vendeur supprime son compte, ses produits sont supprimés aussi
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained()
                  ->onDelete('cascade');

            // Statut du produit
            // - en_attente : le produit attend validation par un admin
            // - actif      : visible par les acheteurs
            // - suspendu   : caché temporairement
            $table->enum('product_status', [
                'en_attente',
                'actif',
                'suspendu'
            ])->default('en_attente')->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'product_status']);
        });
    }
};