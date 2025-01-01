<?php
 
namespace App\Http\Controllers;

use App\Services\GameSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GamesController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function search_games(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required']
        ]);

        if ($validator->passes()) {
            $service = (new GameSearchService($validator->valid()["name"]))->call();

            if ($service->successful) {
                $resp = response()->json($service->result);
            } else {
                $resp = response()->json($service->error, 422);
            }

            return $resp;
            
        } else {
            return response()->json([
                'error' => 'Game name is required'
            ], 422);
        }
    }
}