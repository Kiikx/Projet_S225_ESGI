<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropForeign(['assigned_to_id']);
        $table->dropColumn('assigned_to_id');
    });
}

};
