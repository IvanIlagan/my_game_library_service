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

    public function show(Request $request, $my_game): JsonResponse
    {
        if ($my_game != null) {
            $service = (new MyGamesService())->get_my_game($my_game, Auth::user()->id);

            if ($service->successful) {
                $resp = response()->json($service->result);
            } else {
                $resp = response()->json($service->error, $service->error_status ?? 422);
            }

            return $resp;
        } else {
            return response()->json([
                'error' => 'Data invalid'
            ], 422);
        }
    }

    public function update(Request $request, $my_game) {
        $params = $request->only(["rating", "review", "is_finished", "times_played"]);

        if ($my_game != null) {
            $service = (new MyGamesService())->update_my_game_details($params, $my_game, Auth::user()->id);

            if ($service->successful) {
                $resp = response()->noContent();
            } else {
                $resp = response()->json($service->error, $service->error_status ?? 422);
            }

            return $resp;
        } else {
            return response()->json([
                'error' => 'Data invalid'
            ], 422);
        }
    }

    public function destroy(Request $request, $my_game)
    {
        if ($my_game != null) {
            $service = (new MyGamesService())->delete_my_game($my_game, Auth::user()->id);

            if ($service->successful) {
                $resp = response()->noContent();
            } else {
                $resp = response()->json($service->error, $service->error_status ?? 422);
            }

            return $resp;
        } else {
            return response()->json([
                'error' => 'Data invalid'
            ], 422);
        }
    }
}