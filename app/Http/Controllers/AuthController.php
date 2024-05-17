<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $tokenName = $request->token_name;
            $expires_at = now()->addDay();
            $token = $user->createToken($tokenName, ['*'], $expires_at)->plainTextToken;

            return response()->json([
                'message' => 'Login Success',
                'token' => [
                    'value' => $token,
                    'expires_at' => $expires_at
                ],
            ]);
        }

        return response()->json([
            'message' => 'Login failure - Credential incorrect',
        ], 401);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
    public function user()
    {
        return response()->json([
            'user' => new UserResource(Auth::user())
        ]);
    }
}
