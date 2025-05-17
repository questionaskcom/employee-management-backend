<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\DashboardController;

use Illuminate\Http\Request;


// Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
});


Route::middleware('auth:sanctum')->get('/dashboard', [DashboardController::class, 'index']);
