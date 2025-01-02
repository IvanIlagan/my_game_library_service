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
        Schema::table('player_owned_games', function (Blueprint $table) {
            //
            $table->unique(["player_id", "game_id"], name: "unique_player_and_game_ids");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_owned_games', function (Blueprint $table) {
            //
            $table->dropUnique("unique_player_and_game_ids");
        });
    }
};
