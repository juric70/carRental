<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    // INDEX
    public function index()
    {
        $cars = Car::latest()->take(12)->get();
        return response()->json($cars, 200);
    }
    public function search(Request $request)
    {
        $query = $request->input('q');

        $cars = Car::where('model', 'like', '%' . $query . '%')->get();

        return response()->json($cars, 200);
    }
    // SHOW
    public function show($id)
    {
        try {
            $car = Car::findOrFail($id);
            return response()->json($car);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'license_plate' => 'required|max:255|unique:cars',
                'model' => 'required|max:255',
                'brand' => 'required|max:255',
                'color' => 'required|max:255',
                'year' => 'required|integer',
                'user_id' => 'required|exists:users,id',
                'customer_service_id' => 'required|exists:customer_services,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $car = Car::create([
                'license_plate' => $request->license_plate,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'year' => $request->year,
                'user_id' => $request->user_id,
                'customer_service_id' => $request->customer_service_id,
            ]);

            return response()->json($car, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'license_plate' => 'required|max:255|unique:cars,license_plate,' . $id,
                'model' => 'required|max:255',
                'brand' => 'required|max:255',
                'color' => 'required|max:255',
                'year' => 'required|integer',
                'user_id' => 'required|exists:users,id',
                'customer_service_id' => 'required|exists:customer_services,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $car = Car::findOrFail($id);
            $car->update([
                'license_plate' => $request->license_plate,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'year' => $request->year,
                'user_id' => $request->user_id,
                'customer_service_id' => $request->customer_service_id,
            ]);

            return response()->json($car, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $car = Car::findOrFail($id);
            $car->delete();
            return response()->json('Car deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
