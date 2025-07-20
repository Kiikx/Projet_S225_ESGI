<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * RÉPARATION : Permettre assignation multiple des tâches
     * - Créer table pivot task_user
     * - Migrer les assignations existantes
     * - Supprimer assigned_to_id obsolète
     */
    public function up(): void
    {
        // 1. Créer la table pivot pour assignation multiple
        Schema::create('task_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Éviter les doublons
            $table->unique(['task_id', 'user_id']);
        });

        // 2. Migrer les assignations existantes depuis assigned_to_id
        DB::statement("
            INSERT INTO task_user (task_id, user_id, created_at, updated_at)
            SELECT id, assigned_to_id, created_at, updated_at
            FROM tasks 
            WHERE assigned_to_id IS NOT NULL
        ");

        // 3. Supprimer l'ancienne colonne assigned_to_id
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to_id']);
            $table->dropColumn('assigned_to_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre assigned_to_id
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->onDelete('set null');
        });

        // Migrer les assignations vers assigned_to_id (prendre la première)
        DB::statement("
            UPDATE tasks 
            SET assigned_to_id = (
                SELECT user_id 
                FROM task_user 
                WHERE task_user.task_id = tasks.id 
                LIMIT 1
            )
        ");

        // Supprimer la table pivot
        Schema::dropIfExists('task_user');
    }
};
