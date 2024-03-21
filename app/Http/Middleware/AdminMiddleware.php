<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if ($request->user()->user_category !== 'Admin') {
        //     abort(403, 'Unauthorized');
        // }

        $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

        $user = User::where('jwt_session_token', $jwtToken)->first();
        if($user->user_category !== 'Admin'){
            return response()->json(['error' => 'Unauthorized. You do not have permission to access this resource.']);
        }
        return $next($request);
    }
}
