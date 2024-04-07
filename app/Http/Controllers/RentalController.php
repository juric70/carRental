<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    // INDEX
    public function index()
    {
        $rentals = Rental::all();
        return response()->json($rentals, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $rental = Rental::findOrFail($id);
            return response()->json($rental);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'car_id' => 'required|exists:cars,id',
                'bill_id' => 'required|exists:bills,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $rental = Rental::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'car_id' => $request->car_id,
                'bill_id' => $request->bill_id,
            ]);

            return response()->json($rental, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'car_id' => 'required|exists:cars,id',
                'bill_id' => 'required|exists:bills,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $rental = Rental::findOrFail($id);
            $rental->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'car_id' => $request->car_id,
                'bill_id' => $request->bill_id,
            ]);

            return response()->json($rental, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $rental = Rental::findOrFail($id);
            $rental->delete();
            return response()->json('Rental deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
