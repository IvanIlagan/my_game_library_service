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

    public function get_my_games(int $user_id) {
        $query = (new PlayerOwnedGame)->select(["player_owned_games.game_id AS gb_game_id", "game_details.name AS name", "game_details.image_url"])
                             ->join("game_details", "game_details.gb_game_id", "=", "player_owned_games.game_id")
                             ->where("player_owned_games.player_id", "=", $user_id)
                             ->orderBy("player_owned_games.created_at", "asc")
                             ->get();

        return  $this->create_return_data(result: $query);
    }

    public function get_my_game(string $game_id, int $user_id) {
        $selected_columns = [
            "game_details.name",
            "game_details.image_url",
            "game_details.description",
            "player_owned_games.rating",
            "player_owned_games.review",
            "player_owned_games.is_finished",
            "player_owned_games.times_played"
        ];

        $query = (new PlayerOwnedGame)->select($selected_columns)
                                    ->join("game_details", "game_details.gb_game_id", "=", "player_owned_games.game_id")
                                    ->where("player_owned_games.player_id", "=", $user_id)
                                    ->where("player_owned_games.game_id", "=", $game_id)
                                    ->first();

        if ($query != null) {
            return  $this->create_return_data(result: $query);
        } else {
            return  $this->create_return_data(
                result: '',
                successful: false,
                error: 'Owned game not found.',
                error_http_status: 404
            );
        }
    }

    public function delete_my_game(string $game_id, int $user_id) {
        $query = $this->find_owned_game($user_id, $game_id);

        if ($query != null) {
            if (!$query->delete()) {
                return  $this->create_return_data(
                    result: '',
                    successful: false,
                    error: 'Unable to delete owned game'
                );
            }

            return  $this->create_return_data(result: null);
            
        } else {
            return  $this->create_return_data(
                result: '',
                successful: false,
                error: 'Owned game not found.',
                error_http_status: 404
            );
        }
    }

    private function create_return_data($result, $successful = true, $error = null, $error_http_status = null) {
        $struct = new stdClass ;
        $struct->successful = $successful;
        $struct->result = $result;
        $struct->error = $error;
        $struct->error_status = $error_http_status;

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