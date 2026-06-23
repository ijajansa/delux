<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::employees()->count();
        $activeEmployees = User::employees()->where('is_active', true)->count();
        $inactiveEmployees = User::employees()->where('is_active', false)->count();
        $recentEmployees = User::employees()->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'recentEmployees'
        ));
    }

    public function employeeDashboard()
    {
        return view('employee.dashboard');
    }
}
