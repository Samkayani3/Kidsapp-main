<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
    // try {
    //     $user = JWTAuth::parseToken()->authenticate();
    //     if (!$user) {
    //         throw new UnauthorizedException('User not authenticated');
    //     }
    // } catch (JWTException $e) {
    //     throw new UnauthorizedException('Invalid token');
    // }

    return $next($request);
}
}
