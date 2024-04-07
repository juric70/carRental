<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
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
}
