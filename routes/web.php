<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::redirect('/', '/login');

Route::get('/profile', [AuthController::class, 'showProfile']);
Route::post('/profile', [AuthController::class, 'updateProfile']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword']);
Route::post('/forgot-password', [AuthController::class, 'sendResetPassword']);
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Admin pages (JWT + admin role only)
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::get('/admin/profile', [AuthController::class, 'showAdminProfile']);
});