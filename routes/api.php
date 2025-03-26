<?php

use App\Http\Middleware\{RateLimiter, is_blocked};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, WhitelistController, PasswordController};

Route::group(['middleware' => is_blocked::class], function () {
    Route::group(['middleware' => RateLimiter::class], function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('detect-new-device');
    });
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::middleware('auth')->group(function () {
    Route::apiResource('/whitelist', WhitelistController::class);
});
