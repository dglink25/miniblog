<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne contrainte CHECK
        DB::statement('ALTER TABLE articles DROP CONSTRAINT IF EXISTS articles_status_check');

        // Ajouter la nouvelle contrainte CHECK incluant 'validated'
        DB::statement("
            ALTER TABLE articles
            ADD CONSTRAINT articles_status_check
            CHECK (status IN ('new','pending','rejected','validated'))
        ");
    }

    public function down(): void
    {
        // Supprimer la contrainte mise à jour
        DB::statement('ALTER TABLE articles DROP CONSTRAINT IF EXISTS articles_status_check');

        // Restaurer l'ancienne contrainte (sans 'validated')
        DB::statement("
            ALTER TABLE articles
            ADD CONSTRAINT articles_status_check
            CHECK (status IN ('new','pending','rejected'))
        ");
    }
};
