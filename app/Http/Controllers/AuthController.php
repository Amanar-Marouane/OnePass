<?php

namespace App\Http\Controllers;

use App\HttpResponses;
use App\Models\User;
use App\Models\UserIP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(Request $request)
    {
     $validated = $request->validate([
      'name' => 'required|string',
      'email' => 'required|email',
      'password' => 'required|string',

     ]);
     $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
     ]);

     UserIP::create([
       'user_id' => $user->id,
       'ip_address' => $request->ip(),
     ]);

     return $this->success($user, "User registered successfully", 201);
    }

    public function login(Request $request)
    {
     $request->validate([
      'email' => 'required|email',
      'password' => 'required|string',
     ]);
     $credentials = $request->only('email', 'password');

     if (!$token = Auth::attempt($credentials)) {
      return $this->error('Unauthorized', 401, ['access_token' => $token]);
     }
     $user = Auth::user();
     return $this->success($user, "User logged in successfully",200, ['access_token' => $token]);
    }


    public function logout()
    {
     Auth::logout();
      return $this->success(['message' => 'User logged out successfully']);
    }

}
