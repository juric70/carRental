<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\CustomerService;
use App\Models\User;
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
    public function getForCarCreate()
    {
        $users = User::all();
        $customerServices = CustomerService::all();
        $city = City::all();
        return response()->json(['users'=> $users, 'customerServices'=>$customerServices,  'city'=>$city], 200);
    }
    public function indexAll()
    {
        $cars = Car::with('user', 'customerService')->get();
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
            $car = Car::with('user', 'customerService', 'customerService.city')->findOrFail($id);
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
                'customer_service_id' => 'nullable|exists:customer_services,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            if ($request->filled('customer_service_id') && $request->filled('customer_service')) {
                return response()->json(['error' => 'You cannot provide both customer_service_id and customer_service.'], 422);
            }

            $customerServiceId = $request->customer_service_id;


            if ($request->filled('customer_service')) {

                $validatorCustomerService = Validator::make($request->input('customer_service'), [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'required|max:255',
                    'address' => 'required|max:255',
                ]);
                if ($validatorCustomerService->fails()) {
                    return response()->json($validatorCustomerService->errors(), 422);
                }
                $customerService = CustomerService::create($request->customer_service);
                $customerServiceId = $customerService->id;
            }

            $car = Car::create([
                'license_plate' => $request->license_plate,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'year' => $request->year,
                'user_id' => $request->user_id,
                'customer_service_id' => $customerServiceId,
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
                'customer_service_id' => 'nullable|exists:customer_services,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($request->filled('customer_service_id') && $request->filled('customer_service')) {
                return response()->json(['error' => 'You cannot provide both customer_service_id and customer_service.'], 422);
            }

            $customerServiceId = $request->customer_service_id;


            if ($request->filled('customer_service')) {

                $validatorCustomerService = Validator::make($request->input('customer_service'), [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'required|max:255',
                    'address' => 'required|max:255',
                ]);
                if ($validatorCustomerService->fails()) {
                    return response()->json($validatorCustomerService->errors(), 422);
                }
                $customerService = CustomerService::create($request->customer_service);
                $customerServiceId = $customerService->id;
            }
            $car = Car::findOrFail($id);
            $car->update([
                'license_plate' => $request->license_plate,
                'model' => $request->model,
                'brand' => $request->brand,
                'color' => $request->color,
                'year' => $request->year,
                'user_id' => $request->user_id,
                'customer_service_id' => $customerServiceId,
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
