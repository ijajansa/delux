<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Collection;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $stats = [
            'today_collections' => Collection::where('user_id', $user->id)
                ->where('collected_at', '>=', now()->startOfDay())
                ->count(),
            'total_history' => Collection::where('user_id', $user->id)->count(),
            'active_hotels' => Hotel::where('is_active', true)->count(),
        ];
        
        $recentCollections = Collection::with('hotel')
            ->where('user_id', $user->id)
            ->latest('collected_at')
            ->limit(3)
            ->get();

        return view('employee.dashboard', compact('stats', 'recentCollections'));
    }
}
