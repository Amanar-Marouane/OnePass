<?php

use App\Http\Controllers\WhitelistController;
use App\Http\Middleware\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;

Route::get('/passwords', [PasswordController::class, 'index'])->name("passwords.index");
Route::post('/passwords', [PasswordController::class, 'store'])->name("passwords.store");
Route::patch('/passwords', [PasswordController::class, 'update'])->name("passwords.update");
Route::delete('/passwords/{id}', [PasswordController::class, 'destroy'])->name("passwords.destroy");
