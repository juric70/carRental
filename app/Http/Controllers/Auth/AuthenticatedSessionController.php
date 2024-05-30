<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
