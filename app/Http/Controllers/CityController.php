<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    // INDEX
    public function index()
    {
        $cities = City::all();
        return response()->json($cities, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $city = City::findOrFail($id);
            return response()->json($city);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:cities',
                'code' => 'required|max:255|unique:cities',
                'state_id' => 'required|exists:states,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $city = City::create([
                'name' => $request->name,
                'code' => $request->code,
                'state_id' => $request->state_id,
            ]);

            return response()->json($city, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:cities,name,' . $id,
                'code' => 'required|max:255|unique:cities,code,' . $id,
                'state_id' => 'required|exists:states,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $city = City::findOrFail($id);
            $city->update([
                'name' => $request->name,
                'code' => $request->code,
                'state_id' => $request->state_id,
            ]);

            return response()->json($city, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $city = City::findOrFail($id);
            $city->delete();
            return response()->json('City deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
