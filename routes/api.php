<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', \App\Http\Controllers\Auth\LoginController::class);
    Route::get('logout', \App\Http\Controllers\Auth\LogoutController::class)->middleware('auth:sanctum');
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('me', \App\Http\Controllers\User\MeController::class);
    Route::get('/', \App\Http\Controllers\User\ListController::class);
    Route::get('{user}', \App\Http\Controllers\User\ShowController::class);
});
