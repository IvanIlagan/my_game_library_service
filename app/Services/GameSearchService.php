<?php

namespace App\Services;

use App\Utils\GameDatabaseApi;

class GameSearchService {
    public $successful;
    public $result;
    public $error;

    private $game_name;

    function __construct(string $game_name)
    {
        $this->game_name = $game_name;
    }

    public function call() {
        $response = (new GameDatabaseApi)->search_games($this->game_name);

        if ($response["successful"]) {
            $games = array_map(function ($item) {
                return [
                    "name" => $item["name"],
                    "description" => $item["description"],
                    "imageUrl" => $item["image"]["original_url"]
                ];
            }, $response["data"]);

            $this->result = $games;
            $this->successful = true;
        } else {
            $this->successful = false;
            $this->error = [ "error" => $response["error"] ];
        }

        return $this;
    }
}