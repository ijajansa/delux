@extends('layouts.app')

@section('title', 'Partner Dashboard - Washtrack')

@section('content')
<div class="animate-in">
    <!-- Header Section -->
    <div style="margin-bottom: 24px;">
        <h1 class="page-title">Hello, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
        <p class="page-subtitle">Ready for today's collections?</p>
    </div>

    <!-- Quick Action Card -->
    <div class="card" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.9), rgba(168, 85, 247, 0.9)); border: none; padding: 24px; margin-bottom: 24px; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -20px; top: -20px; opacity: 0.1;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width: 120px; height: 120px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.112 6.345a4.5 4.5 0 0 1-4.474 5.283H4.806a4.5 4.5 0 0 1-4.474-5.283l1.112-6.345a4.5 4.5 0 0 1 4.474-3.807h9.704a4.5 4.5 0 0 1 4.474 3.807Z" /></svg>
        </div>
        <div style="position: relative; z-index: 1;">
            <h2 style="color: white; font-size: 20px; font-weight: 800; margin-bottom: 8px;">New Collection</h2>
            <p style="color: rgba(255, 255, 255, 0.8); font-size: 14px; margin-bottom: 20px; max-width: 200px;">Record bags and items from your hotel visits.</p>
            <a href="{{ route('collections.create') }}" class="btn" style="background: white; color: var(--accent); border: none; font-weight: 700; width: auto; display: inline-flex; padding: 12px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                Start Collecting
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 28px;">
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 24px; font-weight: 800; color: var(--success); margin-bottom: 4px;">{{ $stats['today_collections'] }}</div>
            <div style="font-size: 11px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Today's Visits</div>
        </div>
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 24px; font-weight: 800; color: var(--accent); margin-bottom: 4px;">{{ $stats['total_history'] }}</div>
            <div style="font-size: 11px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Total Records</div>
        </div>
    </div>

    <!-- Management Tools -->
    <div class="section-header">
        <span class="section-title">Master Data</span>
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px;">
        <a href="{{ route('hotels.index') }}" class="card" style="text-decoration:none; padding: 16px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center; font-size: 20px;">🏨</div>
            <div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Hotels</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ $stats['active_hotels'] }} active</div>
            </div>
        </a>
        <a href="{{ route('cloth-types.index') }}" class="card" style="text-decoration:none; padding: 16px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; font-size: 20px;">👕</div>
            <div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Items</div>
                <div style="font-size: 11px; color: var(--text-muted);">Pricing/Types</div>
            </div>
        </a>
    </div>

    <div class="section-header">
        <span class="section-title">Reports</span>
    </div>
    <a href="{{ route('employee.kpi') }}" class="card" style="text-decoration:none; padding: 16px; display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 28px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); display: flex; align-items: center; justify-content: center; font-size: 20px;">📊</div>
            <div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">KPI Report</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ $stats['total_cloths'] }} cloths collected</div>
            </div>
        </div>
        <div style="font-size: 12px; font-weight: 700; color: var(--accent);">Open</div>
    </a>

    <!-- Recent Activity -->
    <div class="section-header">
        <span class="section-title">Recent Collections</span>
        <a href="{{ route('collections.history') }}" style="font-size: 12px; color: var(--accent); font-weight: 600; text-decoration: none;">View All</a>
    </div>

    @forelse($recentCollections as $collection)
        <div class="employee-card" style="padding: 12px;">
            <div class="emp-avatar" style="background: var(--bg-secondary); font-size: 16px;">
                {{ substr($collection->hotel->name, 0, 1) }}
            </div>
            <div class="emp-info">
                <div class="emp-name" style="font-size: 14px;">{{ $collection->hotel->name }}</div>
                <div class="emp-contact" style="font-size: 11px;">{{ $collection->collected_at->diffForHumans() }}</div>
            </div>
            <a href="{{ route('collections.entry', $collection->hotel_id) }}" class="btn btn-secondary btn-sm" style="height: 28px; font-size: 11px; padding: 0 10px;">Edit</a>
        </div>
    @empty
        <div class="empty-state" style="padding: 40px 20px;">
            <div style="font-size: 40px; margin-bottom: 12px; opacity: 0.5;">📦</div>
            <p>No collections yet. Use "Start Collecting" to begin.</p>
        </div>
    @endforelse
</div>
@endsection
