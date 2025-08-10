<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Routes (No Authentication Required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::get('/csrf-cookie', function () {
        return response()->json(['message' => 'CSRF cookie set']);
    })->name('api.auth.csrf-cookie');
});

// Protected Routes (Require Sanctum Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // Authentication Related
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::get('/user', [AuthController::class, 'user'])->name('api.auth.user');
    });

    // Task Management
    Route::apiResource('tasks', TaskController::class)->names([
        'index' => 'api.tasks.index',
        'store' => 'api.tasks.store',
        'show' => 'api.tasks.show',
        'update' => 'api.tasks.update',
        'destroy' => 'api.tasks.destroy'
    ]);
    
    // Additional Task Routes
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
        ->name('api.tasks.toggle');
    Route::get('/tasks/search/{query}', [TaskController::class, 'search'])
        ->name('api.tasks.search');
});

// Fallback Route
Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not found',
        'status' => 404
    ], 404);
});