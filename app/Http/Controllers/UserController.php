<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {
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

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Email or Password Incorrect !'
            ]);
        }
        $user = Auth::user();

        $request->session()->regenerate();

        return response()->json([
            'status' => true,
            'message' => 'User Login Successfuly !',
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete();
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => true,
            'message' => 'Logout Successfuly !',
        ]);
    }
}
