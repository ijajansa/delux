<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
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

// Employee routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('employee.dashboard');
});
