<?php

use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', \App\Http\Controllers\Auth\LoginController::class);
    Route::get('logout', \App\Http\Controllers\Auth\LogoutController::class)->middleware('auth:sanctum');
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('me', \App\Http\Controllers\User\MeController::class);
    Route::get('/', \App\Http\Controllers\User\ListController::class);
});

Route::post('token', function (FcmService $fcmService, Request $request) {
    return $fcmService->send(token: $request->token);
});

Route::post('batch', function (FcmService $fcmService, Request $request) {
    return $fcmService->sendBatchPool(tokens: $request->tokens);
});
