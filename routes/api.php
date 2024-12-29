<?php

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
});
