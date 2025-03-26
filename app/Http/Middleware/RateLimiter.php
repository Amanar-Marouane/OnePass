<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserActivityController;
use App\HttpResponses;
use App\Models\{UserActivity, Block};
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
use Illuminate\Console\Scheduling\Schedule;

class RateLimiter
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $limit;
    private $block_duration; // By hour

    public function handle(Request $request, Closure $next): Response
    {
        $this->limit = $this->limit = (int) env('LOGIN_LIMIT', 10);
        $this->block_duration = (int) env('BLOCK_DURATION', 1);
        $ip = $request->ip();
        UserActivity::create([
            'ip' => $ip,
        ]);

        $current_time = now();
        $one_second_ago = (clone $current_time)->subSecond();

        $log = UserActivity::where('ip', $ip)
            ->whereBetween('created_at', [$one_second_ago, $current_time])
            ->get();
        if (count($log) >= $this->limit) {
            Block::create([
                'duration' => $this->block_duration,
                'ip' => $ip,
            ]);
            cache()->put("blocked_ip:{$ip}", true, now()->addHours($this->block_duration));
            return $this->error('Ip ' . $ip . ' has been blocked for ' . $this->block_duration . ' hour(s) due to excessive requests. Please revise our policy.', 429);
        }

        return $next($request);
    }
}
