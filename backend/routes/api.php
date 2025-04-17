<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ComplaintController;
use App\Http\Controllers\API\QrCodeController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/qr-codes/{uniqueCode}', [QrCodeController::class, 'showByUniqueCode']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Users management
    Route::get('/users', [UserController::class, 'index']);

    // Rooms management
    Route::apiResource('rooms', RoomController::class);

    // QR Codes management
    Route::apiResource('qr-codes', QrCodeController::class);
    Route::post('/qr-codes/generate/{roomId}', [QrCodeController::class, 'generate']);

    // Complaints management (except store which is public)
    Route::apiResource('complaints', ComplaintController::class)->except(['store']);

    // Tasks management
    Route::apiResource('tasks', TaskController::class);
    Route::put('/tasks/{task}/assign', [TaskController::class, 'assign']);
    Route::put('/tasks/{task}/complete', [TaskController::class, 'complete']);
});
