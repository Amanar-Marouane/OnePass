<?php

namespace App\Http\Controllers;

use App\Models\{User, Device};
use App\HttpResponses;
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

        Device::create([
            'user_id' => $user->id,
            'mac_address' => Device::getMacAddress(),
            'is_verified' => "false",
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
        return $this->success($user, "User logged in successfully", 200, ['access_token' => $token]);
    }


    public function logout()
    {
        Auth::logout();
        return $this->success(['message' => 'User logged out successfully']);
    }
}