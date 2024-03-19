<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;

class CheckUserStatus
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

        $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

        if (!$jwtToken) {
            return response()->json(['error' => 'Unauthorized. Token not provided.'], 401);
        }

        // Decode the token to extract the user's ID
        $tokenParts = explode('.', $jwtToken);
        $tokenPayload = json_decode(base64_decode($tokenParts[1]), true);
        $userId = $tokenPayload['sub']; // Assuming the user ID is stored as 'sub' in the token payload

        // Find the user based on the extracted user ID
        $user = User::find($userId);

        if (!$user || $user->jwt_session_token !== $jwtToken) {
            return response()->json(['error' => 'Unauthorized. Invalid token.'], 401);
        }

        return $next($request);
    }
}
