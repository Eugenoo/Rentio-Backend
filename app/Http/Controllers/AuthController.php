<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return "login";
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        $user = User::create($data);

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
                60 * 24,
                '/',
                null,
                true,
                true,
                false,
                'None'
            );
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
        $accessToken = $user->createToken('access', ['*'], now()->addMinutes(15))->plainTextToken;

        $refreshExpires = !empty($data['remember'])
            ? 60 * 24 * 30   // 30 days
            : 60 * 24;       // 1 day

        $refreshToken = $user->createToken('refresh')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ])
        ->cookie(
            'refresh_token',
            $refreshToken,
            $refreshExpires,   // minutes
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
        $user = $request->user();

        // Usuń wszystkie tokeny
        $user->tokens()->delete();

        // Usuwamy również refresh tokeny
        //$user->tokens()->where('name', 'refresh')->delete();

        return response()->json(['logout' => true])
            ->withCookie(cookie()->forget('refresh_token'));
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Refresh token missing'
            ], 401);
        }

        $token = PersonalAccessToken::findToken($refreshToken);

        if (!$token || $token->name !== 'refresh') {
            return response()->json([
                'message' => 'Invalid refresh token'
            ], 403);
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

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($data); // ✅ array

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Email sent'])
            : response()->json(['message' => 'Error'], 400);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset'])
            : response()->json(['message' => 'Invalid token'], 400);
    }
}
