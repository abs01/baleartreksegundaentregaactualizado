<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Trek;
use App\Models\User;
use App\Http\Controllers\Api\TrekController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;  // ⬅️ NUEVO: Controlador API de autenticación

// ========================================
// RUTAS PÚBLICAS (sin autenticación)
// ========================================

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Treks públicos (para que React pueda obtenerlos sin auth)
Route::get('/trek', [TrekController::class, 'index']);
Route::get('/trek/find/{value}', [TrekController::class, 'find']);

// ========================================
// RUTAS PROTEGIDAS
// ========================================

Route::middleware('MULTI-AUTH')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Obtener usuario autenticado
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load('role')
        ]);
    });
    
    // Binding de 'user' (debe estar antes de las rutas que lo utilizan)
    Route::bind('user', function ($value) {
        return is_numeric($value)
            ? User::where('id', $value)->firstOrFail()
            : User::where('dni', $value)->firstOrFail();
    });
    
    // ========================================
    // Rutas de User
    // ========================================
    Route::apiResource('user', UserController::class)->except('index');
    
    // Solo admin puede ver todos los usuarios
    Route::middleware('CHECK-ROLEADMIN')->group(function () {
        Route::apiResource('user', UserController::class)->only('index');
    });
    
    // ========================================
    // Rutas de Trek (protegidas)
    // ========================================
    Route::post('/trek', [TrekController::class, 'store']);
    Route::put('/trek/{trek}', [TrekController::class, 'update']);
    Route::delete('/trek/{trek}', [TrekController::class, 'destroy']);
});