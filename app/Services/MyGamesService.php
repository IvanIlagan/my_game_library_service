<?php

namespace App\Services;

use App\Models\GameDetail;
use App\Models\PlayerOwnedGame;
use stdClass;

class MyGamesService {
    public function add_game(array $params, int $user_id) {

        $game = new GameDetail($params);

        if ($game->save()) {
            $player_owned_game = new PlayerOwnedGame([
                "player_id" => $user_id,
                "game_id" => $game->id
            ]);

            if ($player_owned_game->save()) {
                return $this->create_return_data(result: $player_owned_game);
            } else {
                return $this->create_return_data(
                    result: '',
                    successful: false,
                    error: 'Creation Failed'
                );
            }
        } else {
            return $this->create_return_data(
                result: '',
                successful: false,
                error: 'Creation Failed'
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
}