<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserActivityController;
use App\HttpResponses;
use App\Models\{User, WhiteList, UserActivity, Device, Block};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;

class RateLimiter
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $limit = 10;
    private $block_duration = 1; // By hour

    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $access_token = $request->cookie('access_token');
        $user = JWTAuth::setToken($access_token)->authenticate();
        UserActivityController::store($ip, $user->id);

        $current_time = now();
        $one_second_ago = (clone $current_time)->subSecond();

        $log = UserActivity::where('user_id', $user->id)
            ->whereBetween('created_at', [$one_second_ago, $current_time])
            ->get();
        if (count($log) >= $this->limit) {
            Block::create([
                'duration' => $this->block_duration,
                'user_id' => $user->id,
            ]);
            return $this->success(null, 'User ' . $user->name . ' (ID: ' . $user->id . ') has been blocked for ' . $this->block_duration . ' hour(s) due to excessive requests. Please revise our policy.', 429);
        }
        
        return $next($request);
    }
}
