<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Les statuts du système Escrow qu'on a défini ensemble :
            // 1. en_attente_paiement : commande créée, acheteur n'a pas encore payé
            // 2. paye_retenu         : acheteur a payé, argent bloqué chez nous
            // 3. livraison_en_cours  : vendeur a envoyé le colis
            // 4. confirme_acheteur   : acheteur confirme qu'il a reçu le colis
            // 5. code_envoye_livreur : on envoie le code au livreur
            // 6. termine             : tout est confirmé, vendeur a reçu son argent
            // 7. annule              : commande annulée, acheteur remboursé
            // 8. litige              : problème signalé, en attente d'enquête manuelle
            $table->dropColumn('status');
            $table->enum('status', [
                'en_attente_paiement',
                'paye_retenu',
                'livraison_en_cours',
                'confirme_acheteur',
                'code_envoye_livreur',
                'termine',
                'annule',
                'litige'
            ])->default('en_attente_paiement');

            // Le code de confirmation généré aléatoirement
            // Envoyé au livreur après confirmation de l'acheteur
            $table->string('delivery_code')->nullable();

            // Date limite pour que l'acheteur confirme la réception
            // Si dépassée, l'argent est débloqué automatiquement au vendeur
            $table->timestamp('confirmation_deadline')->nullable();

            // Montant de la commission prélevée sur cette commande (5% ou abonnement)
            $table->decimal('commission_amount', 10, 2)->default(0);

            // Montant final que le vendeur va recevoir
            $table->decimal('seller_amount', 10, 2)->default(0);

            // Numéro de transaction MVola pour traçabilité
            $table->string('mvola_transaction_id')->nullable();
        });

        // On améliore aussi la table order_items qui était vide
        Schema::table('order_items', function (Blueprint $table) {

            // Lien vers la commande principale
            $table->foreignId('order_id')
                  ->after('id')
                  ->constrained()
                  ->onDelete('cascade');

            // Quel produit dans cette commande
            $table->foreignId('product_id')
                  ->after('order_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Quantité commandée de ce produit
            $table->integer('quantity')->after('product_id');

            // Prix unitaire au moment de la commande
            // Important : le prix peut changer après, on garde une trace du prix d'achat
            $table->decimal('unit_price', 10, 2)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'delivery_code', 'confirmation_deadline',
                'commission_amount', 'seller_amount', 'mvola_transaction_id'
            ]);
            $table->string('status')->default('livré');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id', 'product_id']);
            $table->dropColumn(['order_id', 'product_id', 'quantity', 'unit_price']);
        });
    }
};