<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Kid;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KidController extends Controller
{
    public function store(Request $request)
    {
        $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

        $user = User::where('jwt_session_token', $jwtToken)->first();

        if ($user) {

            $kid = new Kid();

            $kid->fill($request->all());

            $kid->user_id = $user->id;

            $kid->save();

            return response()->json(['message' => trans('response.kid_profile_add_success'), 'success'=>true], 200);
        } else {

            return response()->json(['message' => trans('response.err_unauthorized'), 'success'=>false]);
        }
    }

    public function getKidsByUserId(Request $request)
    {
        $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

        $user = User::where('jwt_session_token', $jwtToken)->first();
        $kids = Kid::where('user_id', $user->id)->get(); // Retrieve kids associated with the user ID
        if ($kids->isEmpty()) {
            return response()->json(['message' => 'No kids found for this user']);
        }
        return response()->json($kids);
    }


    public function update(Request $request, $kidId)
    {
        $kid = Kid::findOrFail($kidId);
        $kid->update($request->all());
        return response()->json(['message' => 'Kid data updated successfully']);
    }


    public function delete(Request $request, $kidId)
    {

        $jwtToken = $request->bearerToken();
        $user = User::where('jwt_session_token', $jwtToken)->first();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $kid = Kid::where('user_id', $user->id)->findOrFail($kidId);
            $kid->delete();
            return response()->json(['message' => 'Kid deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Kid not found for this user'], 404);
        }
    }
}
