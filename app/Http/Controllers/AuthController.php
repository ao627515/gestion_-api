<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request){

       $credentials = [
        'phone' => $request->phone,
        'password' => $request->password
       ];


        if (Auth::attempt($credentials)) {
            $user = $request->user();

            $token_name = $request->token_name;
            $token = $user->createToken(
                $token_name, ['*'], now()->addWeek()
            )->plainTextToken;

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
    public function user(){
        return response()->json([
            'user' => new UserResource(Auth::user())
            ]);
    }
}
