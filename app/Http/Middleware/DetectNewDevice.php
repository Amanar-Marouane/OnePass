<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;

class DetectNewDevice
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();


            if (stristr(PHP_OS, 'Darwin')) {
                $macAddress = exec('ifconfig en0 | awk \'/ether/ {print $2}\'');
            } else {
                $macAddress = exec('getmac');
            }

            $device = Device::where('user_id', $user->id)
                ->where('mac_address', $macAddress)
                ->first();

            if (!$device) {


                Device::create([
                    'user_id' => $user->id,
                    'mac_address' => $macAddress,
                    'is_verified' => false,
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
