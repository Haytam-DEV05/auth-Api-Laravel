<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {
        // $validated = $request->validated();
        User::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'user created success, pleas verify your email !'
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password !'
            ]);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password !'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User Login Successfuly !',
            'data' => $user,
            'token' => $token
        ]);
    }

}
