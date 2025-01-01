<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class GameDatabaseApi {
    private $api_key;
    private $response_format;
    private $params;

    function __construct()
    {
        $this->api_key = config('third_party_game_db.api_key');
        $this->response_format = 'json';

        $this->params = [
            "api_key" => $this->api_key,
            "format" => $this->response_format
        ];
    }

    public function search_games(string $name): array {
        $endpoint = "https://www.giantbomb.com/api/games";

        // NOTE: API does a fuzzy search itself. so the user can just search normally
        $filters = [
            "limit" => 20,
            "filter" => "name:{$name}"
        ];

        $response = Http::get($endpoint, array_merge($this->params, $filters));

        // NOTE: When API fails, it returns null json.
        // Create some handler for that scenario
        return [
            "successful" => $response->successful(),
            "status" => $response->status(),
            "data" => $response->json()["results"],
            "error" => $response->json()["error"]
        ];
    }
}