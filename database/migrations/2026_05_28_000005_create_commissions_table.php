<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table qui garde une trace de chaque commission prélevée
        // Très important pour la comptabilité de votre ami en finance
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();

            // La commande sur laquelle on a prélevé la commission
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Le vendeur qui a payé la commission
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');

            // Montant total de la vente
            $table->decimal('sale_amount', 10, 2);

            // Pourcentage prélevé (5% pour amateur, 3% pour pro si vous décidez plus tard)
            $table->decimal('commission_rate', 5, 2);

            // Montant de la commission en Ariary
            // Exemple : vente 100 000 Ar x 5% = 5 000 Ar
            $table->decimal('commission_amount', 10, 2);

            // Montant reversé au vendeur après déduction
            $table->decimal('seller_received', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};