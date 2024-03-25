<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('AuthToken')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
