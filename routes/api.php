<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrganizationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    });

    // Organization Routes
    Route::apiResource('organizations', OrganizationController::class);
    Route::post('/organizations/{organization}/restore', [OrganizationController::class, 'restore'])->name('api.organizations.restore');
    Route::delete('/organizations/{organization}/force-delete', [OrganizationController::class, 'forceDelete'])->name('api.organizations.force-delete');
});
