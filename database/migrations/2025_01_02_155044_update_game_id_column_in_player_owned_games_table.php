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
            $table->dropForeign('owned_games_on_game_details_id');
            $table->string('game_id')->change();
        
            $table->foreign('game_id')
                  ->references('gb_game_id')
                  ->on('game_details');

            $table->index('game_id', 'owned_games_on_game_details_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_owned_games', function (Blueprint $table) {
            //
            $table->dropForeign('player_owned_games_game_id_foreign');
            $table->dropColumn('game_id');

            $table->foreignId('game_id')->constrained(
                table: 'game_details', indexName: 'owned_games_on_game_details_id'
            );
        });
    }
};
