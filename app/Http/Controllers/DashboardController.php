<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function employeeAnalytics(User $user): array
    {
        $collectionsQuery = Collection::where('user_id', $user->id);
        $collectionItemsQuery = CollectionItem::query()
            ->join('collections', 'collection_items.collection_id', '=', 'collections.id')
            ->where('collections.user_id', $user->id);

        $stats = [
            'today_collections' => (clone $collectionsQuery)
                ->where('collected_at', '>=', now()->startOfDay())
                ->count(),
            'total_history' => (clone $collectionsQuery)->count(),
            'total_cloths' => (clone $collectionItemsQuery)->sum('collection_items.quantity'),
            'active_hotels' => Hotel::where('is_active', true)
                ->where('partner_id', $user->id)
                ->count(),
        ];

        $hotelWiseKpis = (clone $collectionsQuery)
            ->join('hotels', 'collections.hotel_id', '=', 'hotels.id')
            ->where('hotels.partner_id', $user->id)
            ->leftJoin('collection_items', 'collections.id', '=', 'collection_items.collection_id')
            ->select(
                'hotels.id',
                'hotels.name',
                DB::raw('COUNT(DISTINCT collections.id) as collection_count'),
                DB::raw('COALESCE(SUM(collection_items.quantity), 0) as cloth_count')
            )
            ->groupBy('hotels.id', 'hotels.name')
            ->orderByDesc('cloth_count')
            ->get();

        $clothTypeWiseKpis = (clone $collectionItemsQuery)
            ->join('cloth_types', 'collection_items.cloth_type_id', '=', 'cloth_types.id')
            ->select(
                'cloth_types.id',
                'cloth_types.name',
                DB::raw('SUM(collection_items.quantity) as cloth_count'),
                DB::raw('COUNT(DISTINCT collections.hotel_id) as hotel_count')
            )
            ->groupBy('cloth_types.id', 'cloth_types.name')
            ->orderByDesc('cloth_count')
            ->get();

        $recentCollections = Collection::with('hotel')
            ->where('user_id', $user->id)
            ->latest('collected_at')
            ->limit(3)
            ->get();

        return compact('stats', 'recentCollections', 'hotelWiseKpis', 'clothTypeWiseKpis');
    }

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
        $analytics = $this->employeeAnalytics(Auth::user());

        return view('employee.dashboard', [
            'stats' => $analytics['stats'],
            'recentCollections' => $analytics['recentCollections'],
        ]);
    }

    public function employeeKpi()
    {
        $analytics = $this->employeeAnalytics(Auth::user());

        return view('employee.kpi', $analytics);
    }
}
