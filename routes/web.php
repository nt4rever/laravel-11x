<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return view('welcome');
});

// Temporarily route login.
Route::get('/login', function () {
    return response(["message" => "Not found."], Response::HTTP_NOT_FOUND);
})->name('login');
