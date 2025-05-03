<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Step `1` : Validate data
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6|max:100'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        try {
            //Step `2` : Creating user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        //Step `1` : Validate data
        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $credentials = ['email' => $request->email, 'password' => $request->password];
        try {
            if (!auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalide credentials']);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'data' => [
                    'auth_token' => $token,
                    'user' => $user
                ]
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function logout(Request $request){
        $request->auth()->user()->currentAccessToken()->delete();

        return response()->json(['status' => 'success', 'message' => 'User logged out successfully']);
    }
}
