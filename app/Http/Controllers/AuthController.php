<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
           'email' => 'email|required',
           'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return response()->json([
               'message' => 'Invalid Credentials',
            ], 422);
        }

        $user = User::query()->where('email', $request->email)->first();

        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response([
           'access_token' => $authToken,
        ]);
    }
}
