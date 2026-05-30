<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table qui gère les abonnements mensuels des vendeurs pro
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Quel vendeur pro a cet abonnement
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Montant de l'abonnement en Ariary
            $table->decimal('amount', 10, 2);

            // Date de début de l'abonnement
            $table->date('start_date');

            // Date de fin — quand l'abonnement expire
            $table->date('end_date');

            // Statut de l'abonnement
            // - actif   : abonnement payé et valide
            // - expire  : date dépassée, vendeur doit renouveler
            // - annule  : vendeur a annulé son abonnement
            $table->enum('status', ['actif', 'expire', 'annule'])->default('actif');

            // Numéro de transaction MVola du paiement de l'abonnement
            $table->string('mvola_transaction_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};