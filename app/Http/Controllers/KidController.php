<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Kid;
use App\Models\User;

class KidController extends Controller
{
    public function store(Request $request)
    {
        $kid = new Kid();
        $kid->fill($request->all());
        $kid->save();
        return response()->json(['message' => 'Kid details added successfully'], 200);
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
        $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

    $user = User::where('jwt_session_token', $jwtToken)->first();

    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Find the kid belonging to the authenticated user
    $kid = Kid::where('user_id', $user->id)->findOrFail($kidId);

    // Delete the kid
    $kid->delete();

    return response()->json(['message' => 'Kid deleted successfully']);
    }
}
