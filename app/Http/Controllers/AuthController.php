<?php

namespace App\Http\Controllers;

use App\Models\{User, Device, LoginActivity};
use App\HttpResponses;
use App\Mail\DetectNewIpAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
       
        LoginActivity::create([
         'user_id' => $user->id,
         'user_ip' => $request->ip(),
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

        $user = User::where('email', $request->email)->first();

        if (!$user || !Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Unauthorized', 401);
        }
    
        $storedIp = LoginActivity::where('user_id', $user->id)->latest()->value('user_ip');
        $isVerified = Device::where('user_id', $user->id)->latest()->value('is_verified');

        if ($isVerified == 'false' && $storedIp != $request->ip()) {
            $verificationCode = rand(100000, 999999);
         Mail::to($user->email)->send(new DetectNewIpAddress($user, $request->ip(), $verificationCode));
         
         return $this->error('Your IP address does not match to your stored IP.', 403);
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