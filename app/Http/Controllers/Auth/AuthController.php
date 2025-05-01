<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        //Step `1` : Validate data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|confirmed|min:6|max:100'
        ]);

        //Step `2` : Creating user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        //Step `3` : Generating token
        $token = $user->createToken('auth_token')->plainTextToken;

        //Step `4` : Return Respons
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'auth_token' => $token,
                'user' => $user
            ]
        ], 201);
    }

}
