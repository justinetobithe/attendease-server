<?php

use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\StrandController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController; 
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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('mobile-login', 'mobileLogin');
    Route::post('validate/google', 'validateGoogleLogin');
    Route::post('login/google', 'loginWithGoogle');
});


Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', MeController::class);

    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::get('/users', [UserController::class, 'index']);
    Route::prefix('/user')->group(function () {
        Route::put('/{id}', [UserController::class, 'update']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    Route::prefix('/strand')->group(function () {
        Route::post('/', [StrandController::class, 'store']);
        Route::get('/{id}', [StrandController::class, 'show']);
        Route::put('/{id}', [StrandController::class, 'update']);
        Route::delete('/{id}', [StrandController::class, 'destroy']);
    });

    Route::get('/students', [StudentController::class, 'index']);

    Route::get('/attendance-records', [AttendanceRecordController::class, 'index']);
    Route::prefix('/attendance-record')->group(function () {
        Route::post('/', [AttendanceRecordController::class, 'store']);
    });
});


Route::get('/strands', [StrandController::class, 'index']);
