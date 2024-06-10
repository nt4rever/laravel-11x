<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => []], function () {
    Route::post('login', \App\Http\Controllers\Auth\LoginController::class);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('me', \App\Http\Controllers\User\MeController::class);
        Route::get('/', \App\Http\Controllers\User\ListController::class);
    });
});
