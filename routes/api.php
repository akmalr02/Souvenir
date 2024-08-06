<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataUserController;
use App\Http\Controllers\Api\SouvenirController;

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');

Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::apiResource('data-user', DataUserController::class)->middleware('auth:api');

Route::apiResource('souvenirs', SouvenirController::class)->middleware('auth:api');
