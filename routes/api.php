<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HallController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function(){
    Route::get('halls', [HallController::class, 'index']);
    
    Route::prefix('auth')->group(function(){
        Route::post('login', [AuthController::class, 'login']);
    });
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page not found'
        ], 404);
});
