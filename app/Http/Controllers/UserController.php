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
        $validated = $request->validated();
        User::create($validated);

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
                'message' => 'Email Incorrect !'
            ]);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentielse !'
            ]);
        }

        // HADA DYAL VERIFICATION EMAIL MAGHADIX NAHTAJO DABA 
        // if (!$user->email_verified_at) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Pleas Verify Your Email !'
        //     ]);
        // }

        return response()->json([
            'status' => true,
            'message' => 'User Login Successfuly !',
            'data' => $user
        ]);
    }
}
