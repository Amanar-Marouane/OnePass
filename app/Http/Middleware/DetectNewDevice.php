<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DetectNewMacAddress;

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

                Mail::to($user->email)->send(new DetectNewMacAddress());
                return response()->json([
                    'message' => 'New device detected. Please verify your device.',
                    'verify' => true
                ], 403);


                logger($user->email . ' has logged in from a new device');
            }
        }

        return $next($request);
    }
}
