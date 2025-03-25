<?php

use App\Http\Middleware\RateLimiter;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => RateLimiter::class], function () {
    Route::get('/', [App\Http\Controllers\test::class, 'index']);
});
