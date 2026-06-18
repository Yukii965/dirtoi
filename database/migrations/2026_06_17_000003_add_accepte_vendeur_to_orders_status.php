<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL ne permet pas de modifier un ENUM via Blueprint sans drop/recreate.
        // On utilise une requête SQL directe qui modifie uniquement la définition
        // de la colonne en ajoutant 'accepte_vendeur' à la liste des valeurs autorisées.
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status ENUM(
                'en_attente_paiement',
                'paye_retenu',
                'accepte_vendeur',
                'livraison_en_cours',
                'confirme_acheteur',
                'code_envoye_livreur',
                'termine',
                'annule',
                'litige'
            ) DEFAULT 'en_attente_paiement'
        ");
    }

    public function down(): void
    {
        // Retire 'accepte_vendeur' si on rollback
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status ENUM(
                'en_attente_paiement',
                'paye_retenu',
                'livraison_en_cours',
                'confirme_acheteur',
                'code_envoye_livreur',
                'termine',
                'annule',
                'litige'
            ) DEFAULT 'en_attente_paiement'
        ");
    }
};