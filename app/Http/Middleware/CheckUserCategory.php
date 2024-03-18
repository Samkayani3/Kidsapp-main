<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckUserCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $category)
    {
        if(auth()->id()){
            $userId = auth()->id();
        }
        else{
        $userId = $request->user_id;
        }
        $user = User::find($userId);

        if ($user) {
            if ($user->user_category === $category) {
                return $next($request);
            } else {
                return response()->json(['error' => 'Unauthorized. You do not have permission to access this resource.']);
            }
        } else {
            return response()->json(['error' => 'User not found.']);
        }
    }
}
