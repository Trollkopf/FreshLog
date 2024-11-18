<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CleaningLogController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// CLEAN
Route::get('/clean', [CleaningLogController::class, 'index']);
Route::post('/clean', [CleaningLogController::class, 'store']);

Route::middleware(['auth', '\App\Http\Middleware\AdminMiddleware::class'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.storeUser');
    Route::post('/admin/store-password', [AdminController::class, 'storePassword'])->name('admin.storePassword');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::patch('/admin/users/{id}/make-admin', [AdminController::class, 'makeAdmin'])->name('admin.makeAdmin');
    Route::post('/admin/change-theme', [AdminController::class, 'changeTheme'])->name('admin.changeTheme');

    Route::get('/admin/configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
    Route::post('/admin/emails', [ConfigurationController::class, 'storeEmail'])->name('emails.store');
    Route::delete('/admin/emails/{id}', [ConfigurationController::class, 'deleteEmail'])->name('emails.delete');
    Route::post('/admin/configurations', [ConfigurationController::class, 'store'])->name('configurations.store');
    Route::put('/admin/configurations/{id}', [ConfigurationController::class, 'update'])->name('configurations.update');});

// GENERATE PDF
Route::get('/generate-pdf', [AdminController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/generate-daily-report', [AdminController::class, 'generateDailyReport'])->name('generate.daily.report');

// LOGIN & REGISTER
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

