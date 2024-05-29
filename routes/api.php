<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => []], function () {
    Route::post('login', \App\Http\Controllers\Auth\LoginController::class);
});

Route::group(['middleware' => ['auth:api']], function () {

});
