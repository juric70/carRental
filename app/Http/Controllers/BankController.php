<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    // INDEX
    public function index()
    {
        $banks = Bank::all();
        return response()->json($banks, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $bank = Bank::findOrFail($id);
            return response()->json($bank);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:banks',
                'code' => 'required|max:255|unique:banks',
                'expiry_date' => 'required|date',
                'cvv' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $bank = Bank::create([
                'name' => $request->name,
                'code' => $request->code,
                'expiry_date' => $request->expiry_date,
                'cvv' => $request->cvv,
            ]);

            return response()->json($bank, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:banks,name,' . $id,
                'code' => 'required|max:255|unique:banks,code,' . $id,
                'expiry_date' => 'required|date',
                'cvv' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $bank = Bank::findOrFail($id);
            $bank->update([
                'name' => $request->name,
                'code' => $request->code,
                'expiry_date' => $request->expiry_date,
                'cvv' => $request->cvv,
            ]);

            return response()->json($bank, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $bank = Bank::findOrFail($id);
            $bank->delete();
            return response()->json('Bank deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
