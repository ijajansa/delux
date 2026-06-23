<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ClothTypeController;
use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/superadmin', [AuthController::class, 'superadminLogin'])->name('login.superadmin');
Route::post('/login/employee', [AuthController::class, 'employeeLogin'])->name('login.employee');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SuperAdmin routes
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('employees', EmployeeController::class)->except(['show', 'destroy']);
    Route::patch('/employees/{id}/toggle', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle');
});

// Admin & Employee shared masters
Route::middleware(['auth'])->group(function () {
    Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
    Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
    Route::patch('/hotels/{hotel}/toggle', [HotelController::class, 'toggle'])->name('hotels.toggle');

    Route::get('/cloth-types', [ClothTypeController::class, 'index'])->name('cloth-types.index');
    Route::post('/cloth-types', [ClothTypeController::class, 'store'])->name('cloth-types.store');
    Route::patch('/cloth-types/{clothType}/toggle', [ClothTypeController::class, 'toggle'])->name('cloth-types.toggle');
});

// Employee routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('employee.dashboard');
    Route::get('/kpi', [DashboardController::class, 'employeeKpi'])->name('employee.kpi');
    Route::get('/collection', [CollectionController::class, 'create'])->name('collections.create');
    Route::get('/collection/{hotel}', [CollectionController::class, 'entry'])->name('collections.entry');
    Route::post('/collection/{hotel}', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('/history', [CollectionController::class, 'history'])->name('collections.history');
});
