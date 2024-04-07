<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    // INDEX
    public function index()
    {
        $feedbacks = Feedback::all();
        return response()->json($feedbacks, 200);
    }

    // SHOW
    public function show($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            return response()->json($feedback);
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
                'rental_id' => 'required|exists:rentals,id',
                'message' => 'required',
                'rating' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $feedback = Feedback::create([
                'user_id' => $request->user_id,
                'rental_id' => $request->rental_id,
                'message' => $request->message,
                'rating' => $request->rating,
            ]);

            return response()->json($feedback, 201);
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
                'rental_id' => 'required|exists:rentals,id',
                'message' => 'required',
                'rating' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $feedback = Feedback::findOrFail($id);
            $feedback->update([
                'user_id' => $request->user_id,
                'rental_id' => $request->rental_id,
                'message' => $request->message,
                'rating' => $request->rating,
            ]);

            return response()->json($feedback, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();
            return response()->json('Feedback deleted successfully', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
