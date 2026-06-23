@extends('layouts.app')

@section('title', 'Employees - Delux Admin')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Employees</h1>
    <p class="page-subtitle">Manage your team members</p>

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Search -->
    <form action="{{ route('employees.index') }}" method="GET">
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="text" name="search" placeholder="Search by name or contact..." value="{{ request('search') }}">
        </div>
    </form>

    <!-- Filter tabs -->
    <div class="filter-tabs">
        <a href="{{ route('employees.index', request()->except('status')) }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">All</a>
        <a href="{{ route('employees.index', array_merge(request()->except('status'), ['status' => 'active'])) }}" class="filter-tab {{ request('status') === 'active' ? 'active' : '' }}">Active</a>
        <a href="{{ route('employees.index', array_merge(request()->except('status'), ['status' => 'inactive'])) }}" class="filter-tab {{ request('status') === 'inactive' ? 'active' : '' }}">Inactive</a>
    </div>

    <!-- Employee List -->
    @forelse($employees as $employee)
        <div class="employee-card">
            <div class="emp-avatar" style="background: linear-gradient(135deg, {{ $employee->is_active ? '#6366f1, #a855f7' : '#475569, #334155' }});">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div class="emp-info">
                <div class="emp-name">{{ $employee->name }}</div>
                <div class="emp-contact">📱 {{ $employee->contact_number }}</div>
            </div>
            <div class="emp-actions">
                <span class="badge {{ $employee->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                </span>
                <div style="display:flex; gap:6px;">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary btn-sm" style="padding:6px 10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    </a>
                    <form action="{{ route('employees.toggle', $employee->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $employee->is_active ? 'btn-danger' : 'btn-success' }} btn-sm" style="padding:6px 10px;">
                            @if($employee->is_active)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <h3>No employees found</h3>
            <p>{{ request('search') ? 'Try a different search term' : 'Add your first employee to get started' }}</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($employees->hasPages())
        <div class="pagination-wrap">
            @if($employees->onFirstPage())
                <span style="opacity:0.3;">← Prev</span>
            @else
                <a href="{{ $employees->previousPageUrl() }}">← Prev</a>
            @endif

            @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                @if($page == $employees->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($employees->hasMorePages())
                <a href="{{ $employees->nextPageUrl() }}">Next →</a>
            @else
                <span style="opacity:0.3;">Next →</span>
            @endif
        </div>
    @endif
</div>

<!-- FAB - Add Employee -->
<a href="{{ route('employees.create') }}" class="fab" title="Add Employee">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
</a>
@endsection
