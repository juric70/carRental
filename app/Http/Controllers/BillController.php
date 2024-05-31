<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    // INDEX
    public function index()
    {
        $bills = Bill::all();
        return response()->json($bills, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $bill = Bill::findOrFail($id);
            return response()->json($bill);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'total' => 'required',
                'status' => 'required',
                'bank_id' => 'required|exists:banks,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $bill = Bill::create([
                'user_id' => $request->user_id,
                'total' => $request->total,
                'status' => $request->status,
                'bank_id' => $request->bank_id,
            ]);

            return response()->json($bill, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'total' => 'required',
                'status' => 'required',
                'bank_id' => 'required|exists:banks,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $bill = Bill::findOrFail($id);
            $bill->update([
                'user_id' => $request->user_id,
                'total' => $request->total,
                'status' => $request->status,
                'bank_id' => $request->bank_id,
            ]);

            return response()->json($bill, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $bill = Bill::findOrFail($id);
            $bill->delete();
            return response()->json('Bill deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function createBill(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'car_id' => 'nullable|exists:cars,id',
            'bank_id'=> 'nullable|exists:banks,id',
            'start_date'=>'required|date',
            'end_date' => 'required|date',
            'total'=>'required|numeric',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->filled('car_id') && $request->filled('car')) {
            return response()->json(['error' => 'You cannot provide both car_id and car details.'], 422);
        }

        if ($request->filled('bank_id') && $request->filled('bank')) {
            return response()->json(['error' => 'You cannot provide both bank_id and bank details.'], 422);
        }

        $carId = $request->car_id;
        $bankId = $request->bank_id;

        if ($request->filled('car')) {
            $validatorCar = Validator::make($request->input('car'), [
                'license_plate' => 'required|string',
                'model' => 'required|string',
                'brand' => 'required|string',
                'color' => 'required|string',
                'year' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'customer_service_id' => 'nullable|exists:customer_services,id',
            ]);

            if ($validatorCar->fails()) {
                return response()->json($validatorCar->errors(), 422);
            }

            $car = Car::create($validatorCar->validated());
            $carId = $car->id;

        }

        if ($request->filled('bank')) {
            $validatorBank = Validator::make($request->input('bank'), [
                'name'=>'required|unique:banks,name',
                'code'=>'required|unique:banks,code',
                'expiry_date'=>'required',
                'cvv'=>'required|unique:banks,cvv',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validatorBank->fails()) {
                return response()->json($validatorBank->errors(), 422);
            }
            $bank = Bank::create($validatorBank->validated());
            $bankId = $bank->id;
        }
        $bill = Bill::create([
            'user_id' => $request->user_id,
            'total' => $request->total,
            'status' => $request->status,
            'bank_id' => $bankId,
        ]);
        $rental = Rental::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'car_id' => $carId,
            'bill_id' => $bill->id
        ]);

        // Return a success response
        return response()->json(['message' => 'Bill created successfully', 'bill_id' => $bill->id], 201);
    }

    public function showRental(){
        $rental = Rental::with('bill')->with('car')->with('bill.bank')->with('bill.user')->get();
        return response()->json($rental);
    }
    public function showUserRental($id){
        // Use a query to get rentals where the related bill has the given user_id
        $rentals = Rental::whereHas('bill.bank', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->with(['bill', 'car', 'bill.bank', 'bill.user'])->get();

        return response()->json($rentals);
    }

    public function showRentalOne($id){
        $rental = Rental::with('bill')->with('car')->with('bill.bank')->with('bill.user')->find($id);
        return response()->json($rental);
    }

    public function getDataForCreate($id){
        $cars = Car::all();
        $banks = Bank::where('user_id',$id)->get();
        return response()->json(['cars'=> $cars, 'banks'=>$banks ]);
    }

    public function updateBill(Request $request, $billId) {
        // Validacija osnovnih polja
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'car_id' => 'nullable|exists:cars,id',
            'bank_id'=> 'nullable|exists:banks,id',
            'start_date'=>'nullable|date',
            'end_date' => 'nullable|date',
            'total'=>'nullable|numeric',
            'status'=>'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Pronalazak računa
        $bill = Bill::find($billId);
        if (!$bill) {
            return response()->json(['error' => 'Bill not found.'], 404);
        }

        // Postavljanje carId i bankId
        $carId = $request->car_id ?? $bill->rental->car_id;
        $bankId = $request->bank_id ?? $bill->bank_id;

        // Kreiranje novog automobila ako su podaci poslani
        if ($request->filled('car')) {
            $validatorCar = Validator::make($request->input('car'), [
                'license_plate' => 'required|string',
                'model' => 'required|string',
                'brand' => 'required|string',
                'color' => 'required|string',
                'year' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'customer_service_id' => 'nullable|exists:customer_services,id',
            ]);

            if ($validatorCar->fails()) {
                return response()->json($validatorCar->errors(), 422);
            }

            $car = Car::create($validatorCar->validated());
            $carId = $car->id;
        }

        // Kreiranje nove banke ako su podaci poslani
        if ($request->filled('bank')) {
            $validatorBank = Validator::make($request->input('bank'), [
                'name'=>'required|unique:banks,name',
                'code'=>'required|unique:banks,code',
                'expiry_date'=>'required',
                'cvv'=>'required|unique:banks,cvv',
                'user_id' => 'nullable|exists:users,id',
            ]);

            if ($validatorBank->fails()) {
                return response()->json($validatorBank->errors(), 422);
            }
            $bank = Bank::create($validatorBank->validated());
            $bankId = $bank->id;
        }

        // Ažuriranje polja računa
        $billData = $request->only(['user_id', 'total', 'status']);
        $billData['bank_id'] = $bankId;

        $bill->update(array_filter($billData)); // array_filter uklanja null vrijednosti

        // Ažuriranje polja najma
        $rentalData = $request->only(['start_date', 'end_date']);
        $rentalData['car_id'] = $carId;
        $rentalData['bank_id'] = $bill->id;

        $bill->rental->update(array_filter($rentalData));

        // Vraćanje uspješne poruke
        return response()->json(['message' => 'Bill updated successfully', 'bill_id' => $bill->id], 200);
    }

    public function showBill($billId){
        $bill = Bill::with('rental')->with('rental.car')->with('user')->with('bank')->find($billId);
        return response()->json($bill);

    }

}
