<?php

use App\Http\Controllers\Tenant\AppointmentController;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\CompanyController;
use App\Http\Controllers\Tenant\ContractorController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\DoctorController;
use App\Http\Controllers\Tenant\MedicalHistoryController;
use App\Http\Controllers\Tenant\MedicineController;
use App\Http\Controllers\Tenant\PatientController;
use App\Http\Controllers\Tenant\PrescriptionController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'tenant.prepare'])->prefix('clinic')->name('tenant.')->group(function () {
    Route::middleware('guest:tenant')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
        Route::post('forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
        Route::get('otp', [AuthController::class, 'showOtp'])->name('otp.show');
        Route::post('otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
        Route::post('otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
        Route::get('reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });

    Route::middleware(['tenant.identify', 'tenant.subscription', 'auth:tenant'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::resource('companies', CompanyController::class)->except(['show']);
        Route::resource('contractors', ContractorController::class)->except(['show']);
        Route::resource('patients', PatientController::class);
        Route::resource('doctors', DoctorController::class)->except(['show']);
        Route::resource('appointments', AppointmentController::class)->except(['show']);
        Route::resource('medicines', MedicineController::class)->except(['show']);
        Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('prescriptions/{prescription}/pdf', [PrescriptionController::class, 'pdf'])->name('prescriptions.pdf');
        Route::resource('medical-histories', MedicalHistoryController::class)->except(['show']);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('change-password', [ProfileController::class, 'editPassword'])->name('password.change');
        Route::put('change-password', [ProfileController::class, 'updatePassword'])->name('password.change.update');
    });
});
