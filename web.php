<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminInventoryController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('users.index');
        Route::get('edit/{id}', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('update/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('delete/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    });

    // Staff Management
    Route::prefix('staff')->group(function () {
        Route::get('/', [AdminStaffController::class, 'index'])->name('staff.index');
        Route::post('/', [AdminStaffController::class, 'store'])->name('staff.store');
        Route::put('update/{id}', [AdminStaffController::class, 'update'])->name('staff.update');
        Route::delete('delete/{id}', [AdminStaffController::class, 'destroy'])->name('staff.delete');
    });

    // Inventory Management
    Route::prefix('inventory')->group(function () {
        Route::get('/', [AdminInventoryController::class, 'index'])->name('inventory.index');
        Route::post('/', [AdminInventoryController::class, 'store'])->name('inventory.store');
        Route::put('update/{id}', [AdminInventoryController::class, 'update'])->name('inventory.update');
        Route::delete('delete/{id}', [AdminInventoryController::class, 'destroy'])->name('inventory.delete');
    });

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // Menu Management
    Route::resource('menu', MenuController::class);
});

/*
|--------------------------------------------------------------------------
| Staff Routes (Cashier + Barista)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff,barista'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('orders', [StaffController::class, 'orders'])->name('orders');
    Route::get('menu', [StaffController::class, 'menu'])->name('menu');
    Route::post('checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('reports', [StaffController::class, 'reports'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('menu', [CustomerController::class, 'menu'])->name('menu');
    Route::get('orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('payments', [CustomerController::class, 'payments'])->name('payments');
    Route::get('profile', [CustomerController::class, 'profile'])->name('profile');
    Route::post('checkout', [OrderController::class, 'checkout'])->name('checkout');
});
