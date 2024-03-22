<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\URL;
use App\Models\UserCategory;
use Illuminate\Support\Facades\DB;



class RegisterController extends Controller
{

    // Display Data of All users
    public function displayAllData(Request $request)
    {

        $users = User::all();

        return response()->json($users, 201);
    }


    public function getUser($id)
{

    $user = User::findOrFail($id);
    return response()->json($user);
}

    // Register User

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'user_category' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $category = UserCategory::where('name', $request->input('user_category'))->value('name');


    if (!$category) {
        return response()->json(['error' => 'Invalid user category'], 422);
    }

    // Generate authentication token
    $auth_token = rand(10000, 99999);

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'user_category' => $category,
        'auth_token' => $auth_token,
    ]);

    // Send user activation email
    $email = new Email();
    $email->send_user_activation_mail($request->name, $request->email, $auth_token);

    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    // Update user details
    $user->jwt_session_token = $token;
    $user->status = 0;
    $user->save();

    return response()->json(['token' => $token], 201);
}

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user_category = $user->user_category;
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        User::where('email', $request->email)->update(['jwt_session_token' => $token]);

        return response()->json(compact('token', 'user_category'));
    }


    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $user = User::where('jwt_session_token', $bearerToken)->first();

        if ($user) {
            $user->jwt_session_token = null;
            $user->save();
            return response()->json(['message' => 'Successfully logged out']);
        } else {
            return response()->json(['error' => 'User not found or not authenticated'], 401);
        }
    }


    // Send Reset Link Email
    public function sendResetLinkEmail(Request $request)
    { {
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $resetUrl = url('api/v1/password-reset-form/' . $user->id);
            $email = new Email();
            $email->send_user_reset_mail($user->email, $resetUrl);

            return response()->json(['message' => 'Password reset link sent successfully'], 200);
        }
    }

    public function createToken(User $user)
    {
        return Password::createToken($user);
    }

    // Show Reset Form
    public function showResetForm(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $email = $user->email;
        $this->reset($request, $email);
        return response()->json(['message' => 'Password reset screen', "Email" => $email]);
    }

    // Reset Password
    public function reset(Request $request, $email)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['message' => 'Password reset successful']);
    }

    // Update User Profile
    public function updateProfile(Request $request)
    {
        $jwtToken = $request->bearerToken();

        $user = User::where('jwt_session_token', $jwtToken)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
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


    public function otpMatch(Request $request)
    {

        $request->validate([
            'otp' => 'required|numeric',
        ]);
        $enteredOTP = $request->otp;
        $jwtToken = $request->bearerToken();

        $user = User::where('jwt_session_token', $jwtToken)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user && $user->auth_token === $enteredOTP) {

            $user->status = 1;
            $user->save();

            return response()->json(['message' => 'OTP matched. Status updated successfully.']);
        } else {

            return response()->json(['error' => 'Wrong OTP code.'], 400);
        }
    }



    public function createUserCategory(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:user_category',
            // Add more validation rules as needed
        ]);

            $userCategory = UserCategory::create([
                'name' => $request->name,
            ]);


            return response()->json(['message' => 'User category created successfully', 'data' => $userCategory], 201);
    }
}
