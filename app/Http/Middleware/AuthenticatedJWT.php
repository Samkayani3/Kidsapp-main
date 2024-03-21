<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthenticatedJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
     try {
         $user = JWTAuth::parseToken()->authenticate();
         if (!$user) {
            return response()->json(['message' => 'User not authenticated']);
         }
     } catch (JWTException $e) {
        //  throw new UnauthorizedException('Invalid token');
        return response()->json(['message' => 'Invalid Token']);
     }

    return $next($request);
}
}
