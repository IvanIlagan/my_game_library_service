<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_details', function (Blueprint $table) {
            //
            $table->string('gb_game_id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_details', function (Blueprint $table) {
            //
            $table->dropUnique('game_details_gb_game_id_unique');
            $table->dropColumn('gb_game_id');
        });
    }
};
