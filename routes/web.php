<?php

use App\Http\Middleware\RateLimiter;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});
