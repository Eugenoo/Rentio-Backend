<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::create($data);

        return $user;
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials.'
            ], 401);
        }

        $user->tokens()->delete();

        //generate new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $user->tokens()->delete();

        return response()->json([
            'logout' => 'true',
            'user' => $user
        ]);
    }
}
