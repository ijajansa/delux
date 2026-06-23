@extends('layouts.app')

@section('title', 'Cloth Types - Delux')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Cloth Types</h1>
    <p class="page-subtitle">Manage items for laundry collection</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom: 24px;">
        <form action="{{ route('cloth-types.store') }}" method="POST" data-offline-form data-offline-label="cloth type">
            @csrf
            <div class="form-group">
                <label class="form-label">Type Name</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" name="name" class="form-input" placeholder="e.g. Bedsheet" required>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>

            @if($canManageClothTypes)
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
        <span class="section-title">{{ $canManageClothTypes ? 'All Types' : 'My Types' }}</span>
    </div>

    @forelse($clothTypes as $type)
        <div class="employee-card">
            <div class="emp-avatar" style="background: var(--bg-secondary);">👕</div>
            <div class="emp-info">
                <div class="emp-name">{{ $type->name }}</div>
                <div class="emp-contact">{{ $type->is_active ? 'Active' : 'Inactive' }}</div>
                @if($canManageClothTypes)
                    <div class="emp-contact">{{ $type->partner?->name ?? 'Unassigned' }}</div>
                @endif
            </div>
            <form action="{{ route('cloth-types.toggle', $type->id) }}" method="POST" data-offline-form data-offline-label="cloth type status">
                @csrf @method('PATCH')
                <button type="submit" class="btn {{ $type->is_active ? 'btn-danger' : 'btn-success' }} btn-sm">
                    {{ $type->is_active ? 'Disable' : 'Enable' }}
                </button>
            </form>
        </div>
    @empty
        <div class="empty-state">{{ $canManageClothTypes ? 'No types added yet.' : 'No types assigned to your account yet.' }}</div>
    @endforelse
</div>
@endsection
