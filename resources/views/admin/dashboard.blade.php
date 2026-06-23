@extends('layouts.app')

@section('title', 'Dashboard - Delux Admin')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, {{ Auth::user()->name }} 👋</p>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-value">{{ $totalEmployees }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card active">
            <div class="stat-value">{{ $activeEmployees }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card inactive">
            <div class="stat-value">{{ $inactiveEmployees }}</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #6366f1, #a855f7); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 15px; font-weight: 600;">Add Employee</h3>
                <p style="font-size: 13px; color: var(--text-secondary);">Register a new team member</p>
            </div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">Add</a>
        </div>
    </div>

    <!-- Recent Employees -->
    <div class="section-header">
        <span class="section-title">Recent Employees</span>
        <a href="{{ route('employees.index') }}" class="section-link">View all →</a>
    </div>

    @forelse($recentEmployees as $employee)
        <div class="employee-card">
            <div class="emp-avatar">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
            <div class="emp-info">
                <div class="emp-name">{{ $employee->name }}</div>
                <div class="emp-contact">📱 {{ $employee->contact_number }}</div>
            </div>
            <span class="badge {{ $employee->is_active ? 'badge-success' : 'badge-danger' }}">
                {{ $employee->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    @empty
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
            <h3>No employees yet</h3>
            <p>Start by adding your first employee</p>
        </div>
    @endforelse
</div>
@endsection
