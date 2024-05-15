<?php

use App\Http\Controllers\HallController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function(){
    Route::get('halls', [HallController::class, 'index']);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page not found'
        ], 404);
});
