<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KarbonController;
use App\Http\Controllers\MissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthUserController::class, 'register']);
Route::post("/login", [AuthUserController::class, 'login']);

Route::middleware('auth:sanctum')->group( function(){
    Route::get("/profile", [AuthUserController::class, 'profile']);
    Route::post("/logout", [AuthUserController::class, 'logout']);

    Route::get("/rank", [KarbonController::class, 'rank']);
    
    Route::get("/category", [CategoryController::class, 'index']);
    Route::post("/category", [CategoryController::class, 'store']);
    Route::delete("/category/{id}", [CategoryController::class, 'destroy']);

    Route::get("/mission", [MissionController::class, 'index']);
    Route::post("/mission", [MissionController::class, 'store']);
    Route::delete("/mission/{id}", [MissionController::class, 'destroy']);

    Route::get('/activity', [ActivityController::class, 'index']);
    Route::post('/activity', [ActivityController::class, 'store']);
    Route::get('/activity/pending', [ActivityController::class, 'getPending']);
    Route::post('/activity/verification/{id}', [ActivityController::class, 'verification']);
});