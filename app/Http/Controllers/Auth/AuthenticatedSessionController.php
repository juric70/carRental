<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken; // Ovo je primer za Laravel Sanctum

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
    public function showAllUsers(){

        $users = User::with('role')->get();
        return response()->json($users);
    }

    public function UpdateUserRole(Request $request, $id){

        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->save();
        return response()->json($user);
    }

    public function UpdateUserProfile(Request $request, $id){

        $user = User::findOrFail($id);
        $user->phone = $request->phone;
        $user->city_id = $request->city_id;
        $user->address = $request->address;
        $user->save();
        return response()->json($user);
    }

    public function getUserProfile($id){

        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function isLoggedIn(){

        if (Auth::check()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
