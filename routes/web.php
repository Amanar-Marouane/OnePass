<?php

use App\Http\Controllers\WhitelistController;
use App\Http\Middleware\RateLimiter;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => RateLimiter::class], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::get('/test', [WhitelistController::class , 'test']);
