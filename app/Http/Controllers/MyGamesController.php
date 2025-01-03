<?php
 
namespace App\Http\Controllers;

use App\Services\MyGamesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MyGamesController extends Controller
{
    public function index(Request $request): JsonResponse
    {

        $service = (new MyGamesService())->get_my_games(Auth::user()->id);

        if ($service->successful) {
            $resp = response()->json($service->result);
        } else {
            $resp = response()->json($service->error, 422);
        }

        return $resp;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
            'platforms' => ['required'],
            'image_url' => ['required'],
            'gb_game_id' => ['required']
        ]);

        if ($validator->passes()) {
            $service = (new MyGamesService())->add_game($validator->valid(), Auth::user()->id);

            if ($service->successful) {
                $resp = response()->noContent(status: 201);
            } else {
                $resp = response()->json($service->error, 422);
            }

            return $resp;
            
        } else {
            return response()->json([
                'error' => 'Data invalid'
            ], 422);
        }
    }
}