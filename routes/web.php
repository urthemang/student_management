<?php

use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Support\Facades\Route;



// Custom student login page route
Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('student.login');
Route::post('/login', [StudentLoginController::class, 'login'])->name('login');
Route::post('/logout', [StudentDashboardController::class, 'logout'])->name('logout');

Route::middleware(['auth:student'])->group(function () {
    Route::get('/', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});
