<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * RÉPARATION : Transformer les catégories en labels globaux
     * - Supprimer project_id (plus de catégories par projet)
     * - Ajouter des labels standards utilisables partout (pour les tâches)
     */
    public function up(): void
    {
        // 1. Supprimer la contrainte project_id des catégories (si elle existe)
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('categories', 'is_terminal')) {
                $table->dropColumn('is_terminal');
            }
        });

        // 2. Vider les catégories existantes (probablement incohérentes)
        // Désactiver les FK checks temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Ajouter des labels standards
        $defaultLabels = [
            ['name' => 'Marketing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Développement', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communication', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Design', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tests', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bug', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Documentation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Réunion', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($defaultLabels);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les labels par défaut
        DB::table('categories')->whereIn('name', [
            'Marketing', 'Développement', 'Communication', 'Design', 
            'Tests', 'Bug', 'Documentation', 'Réunion'
        ])->delete();

        // Remettre les colonnes project_id
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_terminal')->default(false);
        });
    }
};
