<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\TrackingLogController;
use App\Http\Controllers\StatsController;

/*
|--------------------------------------------------------------------------
| API Routes - V1 dengan Gateway Pattern
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // === PUBLIC ROUTES (No Auth) ===
    Route::post('/login', [AuthController::class, 'login']);

    // === AUTHENTICATED ROUTES ===
    Route::middleware('auth:api')->group(function () {

        // Auth endpoints
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // === API GATEWAY ===
        Route::prefix('gateway')->group(function () {

            // Dashboard Stats (admin + user)
            Route::get('/stats', [StatsController::class, 'index']);

            // Container routes - READ (admin + user)
            Route::get('/containers', [ContainerController::class, 'index']);
            Route::get('/containers/{id}', [ContainerController::class, 'show']);

            // Container routes - WRITE (admin only)
            Route::middleware('role:admin')->group(function () {
                Route::post('/containers', [ContainerController::class, 'store']);
                Route::put('/containers/{id}', [ContainerController::class, 'update']);
                Route::patch('/containers/{id}/archive', [ContainerController::class, 'archive']);
                Route::delete('/containers/{id}', [ContainerController::class, 'destroy']);
            });

            // Tracking logs - READ (admin + user)
            Route::get('/containers/{id}/tracking-logs', [TrackingLogController::class, 'index']);

            // Tracking logs - WRITE (admin + operator)
            Route::middleware('role:admin,operator')->group(function () {
                Route::post('/containers/{id}/tracking-logs', [TrackingLogController::class, 'store']);
            });
        });
    });
});