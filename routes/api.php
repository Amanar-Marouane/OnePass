<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->middleware('detect-new-device');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
