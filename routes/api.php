<?php

use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');








Route::get('/passwords', [PasswordController::class, 'index'])->name("passwords.index");
Route::post('/passwords', [PasswordController::class, 'store'])->name("passwords.store");
Route::patch('/passwords', [PasswordController::class, 'update'])->name("passwords.update");
Route::delete('/passwords/{id}', [PasswordController::class, 'destroy'])->name("passwords.destroy");
