<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('priorities')->insert([
            ['label' => 'Basse', 'level' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Moyenne', 'level' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Élevée', 'level' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('priorities')->whereIn('label', ['Basse', 'Moyenne', 'Élevée'])->delete();
    }
};
