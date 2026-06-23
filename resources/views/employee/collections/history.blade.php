@extends('layouts.app')

@section('title', 'History - Delux')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Collection History</h1>
    <p class="page-subtitle">Your past laundry records</p>

    @forelse($collections as $collection)
        <div class="card" style="margin-bottom: 16px; padding: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                <div style="flex: 1;">
                    <h3 style="font-size: 16px; font-weight: 700;">{{ $collection->hotel->name }}</h3>
                    <p style="font-size: 12px; color: var(--text-muted);">{{ $collection->collected_at->format('d M Y, h:i A') }}</p>
                </div>
                <div>
                    @if($collection->collected_at->gt(now()->subDays(3)->startOfDay()))
                        <a href="{{ route('collections.entry', $collection->hotel_id) }}" class="btn btn-secondary btn-sm" style="height: 32px; padding: 0 12px;">Edit</a>
                    @else
                        <div class="badge badge-success">Finalized</div>
                    @endif
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                @foreach($collection->items as $item)
                    <div style="font-size: 13px; display: flex; justify-content: space-between; background: var(--bg-primary); padding: 4px 8px; border-radius: 6px;">
                        <span style="color: var(--text-secondary);">{{ $item->clothType->name }}</span>
                        <span style="font-weight: 700;">{{ $item->quantity }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="empty-state">No history records found.</div>
    @endforelse

    @if($collections->hasPages())
        <div class="pagination-wrap">
            {{ $collections->links() }}
        </div>
    @endif
</div>
@endsection
