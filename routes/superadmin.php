<?php

use App\Http\Controllers\SuperAdmin\AuthController;
use App\Http\Controllers\SuperAdmin\CustomerController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\PlanController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::middleware('guest:superadmin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('superadmin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/renew', [CustomerController::class, 'renew'])->name('customers.renew');
        Route::post('customers/{customer}/reset-password', [CustomerController::class, 'resetPassword'])->name('customers.reset-password');

        Route::resource('plans', PlanController::class)->except(['show']);
    });
});
