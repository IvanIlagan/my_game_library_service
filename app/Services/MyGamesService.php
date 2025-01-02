<?php

namespace App\Services;

use App\Models\GameDetail;
use App\Models\PlayerOwnedGame;
use stdClass;

class MyGamesService {
    public function add_game(array $params, int $user_id) {

        $game = new GameDetail($params);

        if (($this->find_game_by_game_id($game->gb_game_id) != null) || $game->save()) {
            $player_owned_game = new PlayerOwnedGame([
                "player_id" => $user_id,
                "game_id" => $game->gb_game_id
            ]);

            if ($this->find_owned_game($user_id, $game->gb_game_id) != null) {
                return $this->create_return_data(
                    result: '',
                    successful: false,
                    error: [ "error" => "Game already added"]
                );
            }


            if ($player_owned_game->save()) {
                return $this->create_return_data(result: $player_owned_game);
            } else {
                return $this->create_return_data(
                    result: '',
                    successful: false,
                    error: [ "error" => "Creation Failed"]
                );
            }
        } else {
            return $this->create_return_data(
                result: '',
                successful: false,
                error: [ "error" => "Creation Failed"]
            );
        }
    }

    private function create_return_data($result, $successful = true, $error = null) {
        $struct = new stdClass ;
        $struct->successful = $successful;
        $struct->result = $result;
        $struct->error = $error;

        return $struct;
    }

    private function find_game_by_game_id($game_id) {
        return (new GameDetail)->where("gb_game_id", "=", $game_id)->first();
    }

    private function find_owned_game($user_id, $game_id) {
        return (new PlayerOwnedGame)->where("game_id", "=", $game_id)
                                    ->where("player_id", "=", $user_id)
                                    ->first();
    }
}