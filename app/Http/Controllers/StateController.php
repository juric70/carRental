<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    // INDEX
    public function index()
    {
        $states = State::all();
        return response()->json($states, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $state = State::findOrFail($id);
            return response()->json($state);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:states|max:255',
                'code' => 'required|unique:states|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $state = State::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json($state, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:states,name,' . $id . '|max:255',
                'code' => 'required|unique:states,code,' . $id . '|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $state = State::findOrFail($id);
            $state->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            return response()->json($state, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $state = State::findOrFail($id);
            $state->delete();
            return response()->json('State deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
