<?php

use App\Http\Controllers\AccessRightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('halls', [HallController::class, 'index']);
        Route::get('halls/{hall}', [HallController::class, 'show']);
        Route::get('tickets', [TicketController::class, 'index']);
        Route::post('tickets', [TicketController::class, 'store']);
        Route::post('tickets/visitor', [TicketController::class, 'visitorTicketsStore']);
        Route::post('tickets/consumer', [TicketController::class, 'consumerTicketsStore']);
        Route::get('tickets/visitor_tickets', [TicketController::class, 'visitorTickets']);
        Route::get('tickets/consumer_tickets', [TicketController::class, 'consumerTickets']);
        Route::post('user_roles/{user_role}/attribute_access_rights', [UserRoleController::class, 'attributeAccessRights']);
        Route::resource('user_roles', UserRoleController::class);
        Route::resource('access-rights', AccessRightController::class);
    });
    Route::resource('users', UserController::class);

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::delete('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
        });
    });
});

Route::get('login', [AuthController::class, 'create'])->name('login');


Route::fallback(function () {
    return response()->json([
        'message' => 'Page not found'
    ], 404);
});