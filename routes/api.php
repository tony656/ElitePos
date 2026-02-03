<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Security API routes (using web middleware for session-based auth)
Route::middleware('web')->prefix('security')->group(function () {
    Route::get('/sessions', [App\Http\Controllers\systemController::class, 'getActiveSessions']);
    Route::get('/alerts', [App\Http\Controllers\systemController::class, 'getSecurityAlerts']);
    Route::post('/remove-user/{sessionId}', [App\Http\Controllers\systemController::class, 'removeUser']);
    Route::post('/block-all', [App\Http\Controllers\systemController::class, 'blockAllAccess']);
    Route::post('/authorize/{sessionId}', [App\Http\Controllers\systemController::class, 'authorizeSession']);
    Route::post('/suspend-user/{userId}', [App\Http\Controllers\systemController::class, 'suspendUser']);
});
