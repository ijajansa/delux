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
        <form action="{{ route('hotels.store') }}" method="POST" data-offline-form data-offline-label="hotel">
            @csrf
            <div class="form-group">
                <label class="form-label">Hotel Name</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" name="name" class="form-input" placeholder="e.g. Hotel Grand" required>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>

            @if($canManageHotels)
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="partner_id">Assign Partner</label>
                    <select id="partner_id" name="partner_id" class="form-input" required>
                        <option value="">Select partner</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </form>
    </div>

    <div class="section-header">
        <span class="section-title">{{ $canManageHotels ? 'All Hotels' : 'My Hotels' }}</span>
    </div>

    @forelse($hotels as $hotel)
        <div class="employee-card">
            <div class="emp-avatar" style="background: var(--bg-secondary);">🏨</div>
            <div class="emp-info">
                <div class="emp-name">{{ $hotel->name }}</div>
                <div class="emp-contact">{{ $hotel->is_active ? 'Active' : 'Inactive' }}</div>
                @if($canManageHotels)
                    <div class="emp-contact">{{ $hotel->partner?->name ?? 'Unassigned' }}</div>
                @endif
            </div>

            @if($canManageHotels)
                <form action="{{ route('hotels.toggle', $hotel->id) }}" method="POST" data-offline-form data-offline-label="hotel status">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn {{ $hotel->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                        {{ $hotel->is_active ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            @endif
        </div>
    @empty
        <div class="empty-state">{{ $canManageHotels ? 'No hotels added yet.' : 'No hotels assigned to your account yet.' }}</div>
    @endforelse
</div>
@endsection
