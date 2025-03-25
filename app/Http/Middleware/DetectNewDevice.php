<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetectNewDevice
{
    public function handle(Request $request, Closure $next)
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $macAddress = Device::getMacAddress();

            $existingDevice = Device::where('user_id', $user->id)
                ->where('mac_address', $macAddress)
                ->first();

            if (!$existingDevice) {
                Device::create([
                    'user_id' => $user->id,
                    'mac_address' => $macAddress,
                    'is_verified' => "false"
                ]);

                return response()->json([
                    'message' => 'New device detected. Please verify your device.',
                    'verify' => true
                ], 403);
            }
        }

        return $next($request);
    }
}
