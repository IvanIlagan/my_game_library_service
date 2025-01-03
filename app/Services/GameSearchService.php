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
                    "description" => $item["deck"],
                    "image_url" => $item["image"]["original_url"],
                    "platforms" => $this->extract_platform_names($item["platforms"]),
                    "gb_game_id" => $item["guid"]
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

    private function extract_platform_names(array $platforms) {
        return array_map(function ($item) {
            return $item["name"];
        }, $platforms);
    }
}