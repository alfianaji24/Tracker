<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionGroupController;
use App\Http\Controllers\TarifAirController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SwacamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('permission-groups', PermissionGroupController::class);
        Route::resource('tarif-air', TarifAirController::class);
    });

    // Transaction & Other
    Route::resource('pelanggans', PelangganController::class);
    Route::resource('billings', BillingController::class);
    Route::get('/billings/swacam/view', [BillingController::class, 'swacamView'])->name('billings.swacam');

    // API Routes
    Route::get('/api/billings/last-meter/{pelangganId}', [BillingController::class, 'getLastMeter'])->name('api.billings.lastMeter');
    Route::get('/api/billings/generate-invoice', [BillingController::class, 'generateInvoice'])->name('api.billings.generateInvoice');
    Route::post('/api/billings/calculate', [BillingController::class, 'calculateBilling'])->name('api.billings.calculate');

    // swaCam Routes
    Route::post('/swacam/store', [SwacamController::class, 'store'])->name('swacam.store');
    Route::get('/swacam/history', [SwacamController::class, 'history'])->name('swacam.history');
    Route::get('/swacam/archive', [SwacamController::class, 'archive'])->name('swacam.archive');
    Route::post('/swacam/{id}/approve', [SwacamController::class, 'approve'])->name('swacam.approve');
    Route::post('/swacam/{id}/reject', [SwacamController::class, 'reject'])->name('swacam.reject');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
