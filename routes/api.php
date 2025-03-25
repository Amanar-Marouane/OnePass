<?php

use App\Http\Middleware\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::group(['middleware' => RateLimiter::class], function () {});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('detect-new-device');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
