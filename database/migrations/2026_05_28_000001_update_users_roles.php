<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // On remplace l'ancien champ 'role' simple par un enum
            // avec tous les rôles dont on a besoin pour GasyMarket
            $table->dropColumn('role');

            // Les 4 rôles possibles :
            // - acheteur    : quelqu'un qui achète des produits
            // - vendeur_amateur : particulier type Facebook, paie 5% par vente
            // - vendeur_pro : entreprise, paie un abonnement mensuel
            // - admin       : vous les fondateurs, accès total
            $table->enum('role', [
                'acheteur',
                'vendeur_amateur',
                'vendeur_pro',
                'admin'
            ])->default('acheteur');

            // Numéro de téléphone MVola du vendeur
            // Nécessaire pour lui envoyer son argent après une vente
            $table->string('mvola_number')->nullable();

            // Nom de l'entreprise (uniquement pour vendeur_pro)
            $table->string('company_name')->nullable();

            // Statut du compte : actif ou suspendu
            // Un vendeur peut être suspendu s'il ne paie pas son abonnement
            $table->enum('status', ['actif', 'suspendu'])->default('actif');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si on annule cette migration, on remet l'ancien champ simple
            $table->dropColumn(['role', 'mvola_number', 'company_name', 'status']);
            $table->string('role')->default('USER');
        });
    }
};