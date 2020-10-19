<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = JWTAuth::getToken();
        $tokenData = JWTAuth::getPayload($token)->toArray();

        if(Auth::user()->id === $tokenData['sub']) {
            return $next($request);
        } 
    }
}
