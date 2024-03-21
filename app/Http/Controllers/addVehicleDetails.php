<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class addVehicleDetails extends Controller
{

    public function index()
    {
        try {
            $vehicles = Vehicle::all();
            return response()->json($vehicles, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch vehicles'], 500);
        }
    }

    public function store(Request $request)
    {

        $existingVehicle = Vehicle::where('user_id', $request->user_id)->exists();

        if ($existingVehicle) {
            return response()->json(['error' => 'A vehicle is already registered for this Driver'], 400);
        }

        $jwtToken = $request->bearerToken(); // Extract JWT token from Authorization header

        $userId = User::where('jwt_session_token', $jwtToken)->first();
        $vehicle = new Vehicle();
        $vehicle->make = $request['make'];
        $vehicle->model = $request['model'];
        $vehicle->year = $request['year'];
        $vehicle->color = $request['color'];
        $vehicle->user_id = $userId;

        $vehicle->save();
        return response()->json(['message' => 'Vehicle created successfully', 'vehicle' => $vehicle], 201);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        $validatedData = $request->validate([
            'make' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
        ]);

        $vehicle->fill($validatedData);
        $vehicle->save();
        return response()->json(['message' => 'Vehicle updated successfully', 'data' => $vehicle], 200);
    }


    public function destroy($id)
    {
        try {
            if ($id) {
                $vehicle = Vehicle::findOrFail($id);
            } else {
                return response()->json(['error' => 'No Vehicle found with this id'], 500);
            }
            $vehicle->delete();

            return response()->json(['message' => 'Vehicle deleted successfully'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to delete vehicle'], 500);
        }
    }

    public function show(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $user = User::where('jwt_session_token', $bearerToken)->first();
        if ($user && $user->user_category === 'Driver') {
            $vehicles = Vehicle::where('user_id', $user->id)->get();
            return response()->json($vehicles);
        } else {
            return response()->json(['error' => 'User is not authenticated as a driver'], 401);
        }
    }
}
