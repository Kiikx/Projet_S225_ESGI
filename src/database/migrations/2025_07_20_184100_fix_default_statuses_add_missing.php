<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * RÉPARATION : Ajouter les statuses par défaut manquants
     * Le sujet dit : "à faire, en cours, fait, annulé"
     * Kiki a oublié "annulé" !
     */
    public function up(): void
    {
        // Récupérer tous les projets existants
        $projects = DB::table('projects')->get();

        foreach ($projects as $project) {
            $existingStatuses = DB::table('statuses')
                ->where('project_id', $project->id)
                ->pluck('name')
                ->toArray();

            $defaultStatuses = [
                ['name' => 'À faire', 'project_id' => $project->id, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'En cours', 'project_id' => $project->id, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Fait', 'project_id' => $project->id, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Annulé', 'project_id' => $project->id, 'created_at' => now(), 'updated_at' => now()], // KIKI A OUBLIÉ ÇA !
            ];

            // Ajouter uniquement les statuses manquants
            foreach ($defaultStatuses as $status) {
                if (!in_array($status['name'], $existingStatuses)) {
                    DB::table('statuses')->insert($status);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les statuses par défaut si besoin
        DB::table('statuses')
            ->whereIn('name', ['À faire', 'En cours', 'Fait', 'Annulé'])
            ->delete();
    }
};
