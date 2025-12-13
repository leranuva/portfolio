<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/portfolio/public', [PortfolioController::class, 'publicData']);

// Auth routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile
    Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::put('/profile/password', [\App\Http\Controllers\Api\ProfileController::class, 'updatePassword']);

    // Clients
    Route::apiResource('clients', ClientController::class);

    // Projects
    Route::apiResource('projects', ProjectController::class);

    // Skills
    Route::apiResource('skills', SkillController::class);

    // Portfolio Settings
    Route::get('/portfolio/settings', [PortfolioController::class, 'getSettings']);
    Route::put('/portfolio/settings', [PortfolioController::class, 'updateSettings']);
    Route::get('/portfolio/settings/{key}', [PortfolioController::class, 'getSetting']);
    Route::put('/portfolio/settings/{key}', [PortfolioController::class, 'updateSetting']);
});

