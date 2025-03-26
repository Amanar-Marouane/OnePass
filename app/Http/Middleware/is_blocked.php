<?php

namespace App\Http\Middleware;

use App\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\{Block};

class is_blocked
{
    use HttpResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $is_blocked = Block::where('ip', $ip)->first();
        if ($is_blocked) {
            $waiting_until_resolve = $is_blocked->created_at->addMinutes((int)env('BLOCK_DURATION') * 60);
            return $this->error('Your ip is temporarily blocked. You will regain access on ' . $waiting_until_resolve . '.', 403);
        }

        return $next($request);
    }
}
