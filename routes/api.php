<?php

use App\Http\Controllers\GamesController;
use App\Http\Controllers\MyGamesController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(["prefix" => 'v1'], function () {
    Route::post('/sign_up', [SignUpController::class, 'sign_up']);
    Route::post('/login', [SessionController::class, 'authenticate']);

    Route::get('/games', [GamesController::class, 'search_games'])->middleware('auth:sanctum');

    Route::resource('my_games', MyGamesController::class)->only([
        'store'
    ])->middleware('auth:sanctum');
});
