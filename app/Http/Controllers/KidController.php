<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Kid;

class KidController extends Controller
{
    public function store(Request $request)
    {
        $kid = new Kid();
        $kid->fill($request->all());
        $kid->save();
        return response()->json(['message' => 'Kid details added successfully'], 200);
    }

    public function getKidsByUserId($userId)
    {
        $kids = Kid::where('user_id', $userId)->get();
        return response()->json($kids);
    }


    public function update(Request $request, $kidId)
    {
        $kid = Kid::findOrFail($kidId);
        $kid->update($request->all());
        return response()->json(['message' => 'Kid data updated successfully']);
    }


    public function delete($kidId)
    {
        $kid = Kid::findOrFail($kidId);
        $kid->delete();
        return response()->json(['message' => 'Kid deleted successfully']);
    }
}
