<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * RÉPARATION : Ajouter le concept de colonne terminale
     * Selon le sujet : "l'une de ces colonne doit pouvoir être considérée comme terminale"
     * - "Fait" = terminal et non-supprimable
     * - "À faire", "En cours" = non-supprimables (workflow obligatoire)  
     * - "Annulé" = supprimable
     */
    public function up(): void
    {
        // 1. Ajouter le champ is_terminal
        Schema::table('statuses', function (Blueprint $table) {
            $table->boolean('is_terminal')->default(false)->after('name');
        });

        // 2. Marquer "Fait" comme terminal dans tous les projets
        DB::table('statuses')
            ->where('name', 'Fait')
            ->update(['is_terminal' => true]);

        // 3. Ajouter un champ pour empêcher la suppression des statuses essentiels
        Schema::table('statuses', function (Blueprint $table) {
            $table->boolean('is_protected')->default(false)->after('is_terminal');
        });

        // 4. Protéger les statuses essentiels (À faire, En cours, Fait)
        // "Annulé" reste supprimable
        DB::table('statuses')
            ->whereIn('name', ['À faire', 'En cours', 'Fait'])
            ->update(['is_protected' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropColumn(['is_terminal', 'is_protected']);
        });
    }
};
