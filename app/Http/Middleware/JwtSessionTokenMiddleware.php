<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class JwtSessionTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

    //     if (!$jwtToken) {
    //         return response()->json(['error' => 'Unauthorized. Token not provided.'], 401);
    //     }

    //     $user = User::where('jwt_session_token', $jwtToken)->first();


    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthorized. Invalid token.'], 401);
    //     }


    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next)
{
    $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

    if (!$jwtToken) {
        return response()->json(['error' => 'Unauthorized. Token not provided.'], 401);
    }

    $user = User::where('jwt_session_token', $jwtToken)->first();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized. Invalid token.'], 401);
    }

    // Check if the user's status is inactive
    if ($user->status === 0) {
        return response()->json(['error' => 'Access denied. User status is inactive.'], 403);
    }

    return $next($request);
}
}
