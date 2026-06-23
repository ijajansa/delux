@extends('layouts.app')

@section('title', 'Collection Entry - Delux')

@section('content')
<div class="animate-in">
    <a href="{{ route('collections.create') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--accent);font-size:14px;font-weight:600;text-decoration:none;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
        Back
    </a>

    <h1 class="page-title">{{ $hotel->name }}</h1>
    <p class="page-subtitle">{{ isset($collection) ? 'Update' : 'Enter' }} cloth quantities</p>

    <form action="{{ route('collections.store', $hotel->id) }}" method="POST">
        @csrf
        <div class="card" style="margin-bottom: 24px;">
            @forelse($clothTypes as $type)
                <div class="form-group" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 16px;">
                    <label class="form-label" style="margin-bottom: 0; flex: 1;">{{ $type->name }}</label>
                    <input type="number" 
                           name="quantities[{{ $type->id }}]" 
                           class="form-input" 
                           style="width: 100px; text-align: center; font-size: 18px; font-weight: 700; border-color: {{ isset($existingQuantities[$type->id]) ? 'var(--success)' : '' }};" 
                           placeholder="0" 
                           value="{{ $existingQuantities[$type->id] ?? '' }}"
                           min="0" 
                           inputmode="numeric">
                </div>
            @empty
                <div class="empty-state">No cloth types defined.</div>
            @endforelse
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="height: 54px;">{{ isset($collection) ? 'Update Collection' : 'Save Collection' }}</button>
    </form>
</div>
@endsection
