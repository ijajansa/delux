@extends('layouts.app')

@section('title', 'Hotels - Delux')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Hotels</h1>
    <p class="page-subtitle">Manage laundry collection points</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom: 24px;">
        <form action="{{ route('hotels.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Hotel Name</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" name="name" class="form-input" placeholder="e.g. Hotel Grand" required>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>

    <div class="section-header">
        <span class="section-title">All Hotels</span>
    </div>

    @forelse($hotels as $hotel)
        <div class="employee-card">
            <div class="emp-avatar" style="background: var(--bg-secondary);">🏨</div>
            <div class="emp-info">
                <div class="emp-name">{{ $hotel->name }}</div>
                <div class="emp-contact">{{ $hotel->is_active ? 'Active' : 'Inactive' }}</div>
            </div>
            <form action="{{ route('hotels.toggle', $hotel->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $hotel->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                    {{ $hotel->is_active ? 'Disable' : 'Enable' }}
                </button>
            </form>
        </div>
    @empty
        <div class="empty-state">No hotels added yet.</div>
    @endforelse
</div>
@endsection
