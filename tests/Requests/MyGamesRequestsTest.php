<?php

use App\Models\User;
use App\Models\GameDetail;
use App\Models\PlayerOwnedGame;

describe('POST /api/v1/my_games', function () {
    describe("when user is not authenticated", function () {
        it('returns http status 401', function () {
            $response = $this->postJson('/api/v1/my_games', ['game_id' => 1]);

            $response->assertStatus(401);
            expect((new GameDetail)->count())->toBe(0);
            expect((new PlayerOwnedGame)->count())->toBe(0);
        });
    });

    describe("when user is authenticated", function () {
        beforeEach(function () {
            $this->user = User::factory()->create();
        });

        describe("when user has valid params", function () {
            it ('successfully adds the game in my library', function () {
                $response = $this->actingAs($this->user)
                     ->withSession(['banned' => false])
                     ->postJson('/api/v1/my_games', [
                        'name' => 'Game 1',
                        'description' => 'This is a game',
                        'platforms' => 'PC',
                        'image_url' => 'www.google.com',
                        'gb_game_id' => 1
                    ]);

                $response->assertStatus(201);
                expect((new GameDetail)->count())->toBe(1);
                expect((new PlayerOwnedGame)->count())->toBe(1);
            });

            describe("when selected game is already in my library", function () {
                it ('fails to add the game to my library', function () {
                    $game = GameDetail::factory()->create();
                    PlayerOwnedGame::factory()->create([
                        "player_id" => $this->user->id,
                        "game_id" => $game->gb_game_id
                    ]);

                    $response = $this->actingAs($this->user)
                     ->withSession(['banned' => false])
                     ->postJson('/api/v1/my_games', [
                        'name' => $game->name,
                        'description' => $game->description,
                        'platforms' => $game->platforms,
                        'image_url' => $game->image_url,
                        'gb_game_id' => $game->gb_game_id
                    ]);

                    $response->assertStatus(422);
                    expect((new PlayerOwnedGame)->count())->toBe(1);
                });
            });
        });

        describe("when user has invalid params", function () {
            it ('fails to add the game in my library', function () {
                $response = $this->actingAs($this->user)
                     ->withSession(['banned' => false])
                     ->postJson('/api/v1/my_games', [
                        'test' => 'test'
                    ]);

                $response->assertStatus(422);
                expect((new GameDetail)->count())->toBe(0);
                expect((new PlayerOwnedGame)->count())->toBe(0);
            });
        });
    });
});