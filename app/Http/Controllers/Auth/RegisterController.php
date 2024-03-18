<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\User as Authenticatable;


class RegisterController extends Controller
{

    //DIsplay Data of All users
    public function displayAllData(Request $request){
        $users = User::all(); //Fetch all users
        return response()->json($users, 201);
        // return view('welcome', compact('users'));
    }

    // Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_category' => 'required|in:Driver,Parent',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $role = $request->input('user_category', 'Driver');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_category' => $role,
        ]);

        $token = JWTAuth::fromUser($user);
        $user->user_category = $request->user_category;
        $user->jwt_session_token = $token;
        $user->save();
        return response()->json(['token' => $token], 201);
    }


//  Login With JWT Auth


//     public function login(Request $request)
// {
//     $credentials = $request->only('email', 'password');

//     try {
//         if (! $token = JWTAuth::attempt($credentials)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         $user = User::where('email', $request->email)->first();
//         if (!$user) {
//             return response()->json(['error' => 'User not found'], 404);
//         }

//         $user_category = $user->user_category;

//     } catch (JWTException $e) {
//         return response()->json(['error' => 'Could not create token'], 500);
//     }

//     User::where('email', $request->email)->update(['jwt_session_token' => $token]);

//     return response()->json(compact('token', 'user_category'));
// }


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    try {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user_category = $user->user_category;

        // Save the token in the bearer token
        $request->headers->set('Authorization', 'Bearer ' . $token);

    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }

    User::where('email', $request->email)->update(['jwt_session_token' => $token]);

    return response()->json(compact('token', 'user_category'));
}

    // Logout
    public function logout(Request $request)
    {
         $email = $request->input('email');
            if(!Auth::user()){
            $user = User::where('email', $email)->first();
            }
            else{
            $user = Auth::user();
            }
           if ($user) {
        $user = User::find($user->id);
        $user->jwt_session_token = null;
        $user->save();
        return response()->json(['message' => 'Successfully logged out']);
        }
    }





    // Send Reset Link Email
    public function sendResetLinkEmail(Request $request)
    {

        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? response()->json(['message' => __($response)], 200)
                    : response()->json(['error' => __($response)], 400);
    }

    // Show Reset Form
    public function showResetForm(Request $request, $token = null)
    {
        return response()->json(['token' => $token, 'email' => $request->email]);
        // return response()->json(['email' => $request->email]);
    }

    // Reset/Update Password

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8|confirmed',
        ]);

        $response = Password::reset($request->only(
            'email', 'password', 'password_confirmation', 'token'
        ), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        });

        return $response == Password::PASSWORD_RESET
                    ? response()->json(['message' => __($response)], 200)
                    : response()->json(['error' => __($response)], 400);
    }


    // Update User Profile
    public function updateProfile(Request $request)
    {
    $userId = Auth::id();

    if($userId){
        $user = User::where('email', $request->input('email'))
                ->where('id', $userId)
                ->first();
    }
    else{
        $user = User::where('email', $request->input('email'))->first();
    }
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        // 'email' => 'required|string|email|max:255|unique:users,email,',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if ($request->filled('name')) {
        $user->name = $request->input('name');
    }
    if ($request->filled('email')) {
        $user->email = $request->input('email');
    }
    if ($request->filled('password')) {
        $user->password = Hash::make($request->input('password'));
    }

    $user->save();

    return response()->json(['message' => 'Profile updated successfully'], 200);
    }

}
