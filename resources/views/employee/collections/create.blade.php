@extends('layouts.app')

@section('title', 'Select Hotel - Delux')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Select Hotel</h1>
    <p class="page-subtitle">Where are you collecting from?</p>

    <div class="search-bar">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
        <input type="text" id="hotelSearch" placeholder="Search hotels...">
    </div>

    <div id="hotelList">
        @forelse($hotels as $hotel)
            <a href="{{ route('collections.entry', $hotel->id) }}" class="employee-card hotel-item" data-name="{{ strtolower($hotel->name) }}">
                <div class="emp-avatar">🏨</div>
                <div class="emp-info">
                    <div class="emp-name">{{ $hotel->name }}</div>
                    @if($hotel->collected_today)
                        <div class="emp-contact" style="color:var(--success);">✅ Collected Today</div>
                    @endif
                </div>
                <div class="btn {{ $hotel->collected_today ? 'btn-secondary' : 'btn-primary' }} btn-sm">
                    {{ $hotel->collected_today ? 'Edit' : 'Select' }}
                </div>
            </a>
        @empty
            <div class="empty-state">No active hotels available.</div>
        @endforelse
    </div>
</div>

<script>
    document.getElementById('hotelSearch').addEventListener('input', function(e) {
        let search = e.target.value.toLowerCase();
        document.querySelectorAll('.hotel-item').forEach(item => {
            let name = item.dataset.name;
            item.style.display = name.includes(search) ? 'flex' : 'none';
        });
    });
</script>
@endsection
