<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function index()
    {
        return "login";
    }

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

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials.'
            ], 401);
        }

        $user->tokens()->delete();

        //generate new token
        $accessToken = $user->createToken('access', ['*'])->plainTextToken;
        $refreshToken = $user->createToken('refresh')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ])
        ->cookie(
            'refresh_token',
            $refreshToken,
            60 * 24,   // minutes
            '/',       // path
            null,      // domain (null = use current domain)
            true,      // Secure (required for SameSite=None)
            true,      // HttpOnly
            false,     // raw
            'None'     // SameSite=None  <-- this is the important fix
        );
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

    public function refresh(Request $request)
    {
        $cookie = $request->cookie('refresh_token');

        if (!$cookie) {
            return response()->json(['message' => "refresh token is missing"], 401);
        }

        $token = PersonalAccessToken::findToken($cookie);

        if (!$token) {
            return response()->json(['message' => "refresh token is missing"], 401);
        }

        $user = $token->tokenable;

        // new tokens
        $newAccessToken = $user->createToken('access', ['*'])->plainTextToken;
        $newRefreshToken = $user->createToken('refresh')->plainTextToken;

        // delete previously used refresh token
        $token->delete();

        $cookie = cookie(  'refresh_token',
            $newRefreshToken,
            60 * 24,    // minutes
            '/',
            null,
            true,        // Secure
            true,        // HttpOnly
            false,
            'None'      // SameSite=None
        );

        return response()
            ->json([
                'access_token' => $newAccessToken,
                'user' => $user
            ])
            ->cookie($cookie);
    }
//    public function refresh(Request $request)
//    {
//        $cookie = request()->cookie('refresh_token');
//
//        if(!$cookie ) {
//            return response()->json(['message' => "refresh token is missing"], 401);
//        }
//
//        $token = PersonalAccessToken::findToken($cookie);
//
//        if(!$token) {
//            return response()->json(['message' => "refresh token is missing"], 401);
//        }
//
//        $user = $token->tokenable;
//
//        $newAccessToken = $user->createToken('access', ['*'])->plainTextToken;
//
//        return response()->json([
//            'access_token' => $newAccessToken,
//            'user' => $user
//        ]);
//    }
}
