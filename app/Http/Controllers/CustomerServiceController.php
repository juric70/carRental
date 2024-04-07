<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerService;
use Illuminate\Support\Facades\Validator;

class CustomerServiceController extends Controller
{
    // INDEX
    public function index()
    {
        $services = CustomerService::all();
        return response()->json($services, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $service = CustomerService::findOrFail($id);
            return response()->json($service);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required|max:255',
                'email' => 'required|email|max:255|unique:customer_services',
                'address' => 'nullable|max:255',
                'city_id' => 'required|exists:cities,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $service = CustomerService::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city_id' => $request->city_id,
            ]);

            return response()->json($service, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required|max:255',
                'email' => 'required|email|max:255|unique:customer_services,email,' . $id,
                'address' => 'nullable|max:255',
                'city_id' => 'required|exists:cities,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $service = CustomerService::findOrFail($id);
            $service->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city_id' => $request->city_id,
            ]);

            return response()->json($service, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $service = CustomerService::findOrFail($id);
            $service->delete();
            return response()->json('Customer service deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
