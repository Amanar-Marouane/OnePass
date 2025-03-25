<?php

namespace App\Http\Middleware;

use App\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class JWTVal
{
   use HttpResponses;
 /**
  * Handle an incoming request.
  *
  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
  */
 public function handle(Request $request, Closure $next): Response
 {
     $access_token = $request->cookie('access_token');

     if (!$access_token) {
         return $this->error("Access token not found",401);
     }

     $user = JWTAuth::setToken($access_token)->authenticate();
     
     if (!$user) {
         return $this->error("Unauthorized", 401);
     }

    return $next($request);
 }
}
