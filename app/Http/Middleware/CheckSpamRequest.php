<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BlackList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckSpamRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestIp = $request->ip();
        $cashkey = 'requests:'.$requestIp;
        $blackList = BlackList::where('ip' , $requestIp)->first();
        if($blackList){
            return response()->json([
                "message"=>"Your ip is Blacklisted"
            ],403);
        }
        $requests = Cache::get($cashkey,[]);
        $requests = array_filter($requests,function($timestamp){
            return $timestamp > now()->subSecond(1)->timestamp;
        });
        if(count($requests) > 15){
            BlackList::create([
                'ip'=>$requestIp
            ]);
            return response()->json([
                "message"=>"You Have sent Too many Requests , You have been Blacklisted"
            ]);
        }
        $requests[]= now()->timestamp;
        Cache::put($cashkey,$requests,60);
        return $next($request);
    }
}
