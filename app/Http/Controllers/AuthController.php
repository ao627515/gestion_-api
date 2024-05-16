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

            $existingToken = $user->tokens()->where('name', $tokenName)->first();

            if ($existingToken && $existingToken->expires_at >= now()) {
                $token = $existingToken->plainTextToken;
            } elseif ($existingToken) {
                $existingToken->delete();
            }

            $token = $user->createToken($tokenName, ['*'], now()->addWeek())->plainTextToken;

            return response()->json([
                'message' => 'Login Success',
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Login failure',
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
