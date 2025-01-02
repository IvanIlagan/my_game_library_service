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
        Schema::create('player_owned_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained(
                table: 'users', indexName: 'owned_games_on_users_id'
            );
            $table->foreignId('game_id')->constrained(
                table: 'game_details', indexName: 'owned_games_on_game_details_id'
            );
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->integer('times_played')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_owned_games');
    }
};
