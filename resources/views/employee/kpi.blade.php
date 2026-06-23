@extends('layouts.app')

@section('title', 'KPI Report - Delux')

@section('styles')
<style>
    .kpi-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .kpi-summary-value {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--accent);
    }

    .kpi-summary-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-panel {
        display: none;
    }

    .kpi-panel.active {
        display: block;
    }

    .kpi-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .kpi-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 16px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg-secondary);
    }

    .kpi-label {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .kpi-meta {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .kpi-value {
        text-align: right;
        font-size: 20px;
        font-weight: 800;
        color: var(--accent);
        white-space: nowrap;
    }

    .kpi-value span {
        display: block;
        margin-top: 2px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .kpi-empty {
        text-align: center;
        padding: 28px 20px;
        color: var(--text-muted);
        border: 1px dashed var(--border);
        border-radius: 12px;
        background: var(--bg-secondary);
    }

    @media (min-width: 768px) {
        .kpi-summary-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
</style>
@endsection

@section('content')
@php
    $topHotel = $hotelWiseKpis->first();
    $topClothType = $clothTypeWiseKpis->first();
@endphp

<div class="animate-in">
    <a href="{{ route('employee.dashboard') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--accent);font-size:14px;font-weight:600;text-decoration:none;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
        Back
    </a>

    <h1 class="page-title">KPI Report</h1>
    <p class="page-subtitle">Hotel wise and cloth type wise collection breakdowns</p>

    <div class="kpi-summary-grid">
        <div class="card" style="padding: 16px;">
            <div class="kpi-summary-value">{{ $stats['total_cloths'] }}</div>
            <div class="kpi-summary-label">Cloths Collected</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div class="kpi-summary-value">{{ $stats['total_history'] }}</div>
            <div class="kpi-summary-label">Total Records</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div class="kpi-summary-value">{{ $hotelWiseKpis->count() }}</div>
            <div class="kpi-summary-label">Hotels Visited</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div class="kpi-summary-value">{{ $clothTypeWiseKpis->count() }}</div>
            <div class="kpi-summary-label">Cloth Types Used</div>
        </div>
    </div>

    <div class="filter-tabs" role="tablist" aria-label="KPI filters">
        <button class="filter-tab active" type="button" data-kpi-tab="all">All</button>
        <button class="filter-tab" type="button" data-kpi-tab="hotel">Hotel Wise</button>
        <button class="filter-tab" type="button" data-kpi-tab="cloth">Cloth Type Wise</button>
    </div>

    <div class="kpi-panel active" data-kpi-panel="all">
        <div class="card" style="padding: 16px; margin-bottom: 16px;">
            <div class="section-header" style="margin-bottom: 12px;">
                <span class="section-title">Top Hotels</span>
            </div>

            @if($hotelWiseKpis->isEmpty())
                <div class="kpi-empty">No hotel breakdown available yet.</div>
            @else
                <div class="kpi-list">
                    @foreach($hotelWiseKpis->take(3) as $hotelRow)
                        <div class="kpi-row">
                            <div>
                                <div class="kpi-label">{{ $hotelRow->name }}</div>
                                <div class="kpi-meta">{{ $hotelRow->collection_count }} records</div>
                            </div>
                            <div class="kpi-value">
                                {{ $hotelRow->cloth_count }}
                                <span>cloths</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card" style="padding: 16px;">
            <div class="section-header" style="margin-bottom: 12px;">
                <span class="section-title">Top Cloth Types</span>
            </div>

            @if($clothTypeWiseKpis->isEmpty())
                <div class="kpi-empty">No cloth type breakdown available yet.</div>
            @else
                <div class="kpi-list">
                    @foreach($clothTypeWiseKpis->take(3) as $clothRow)
                        <div class="kpi-row">
                            <div>
                                <div class="kpi-label">{{ $clothRow->name }}</div>
                                <div class="kpi-meta">{{ $clothRow->hotel_count }} hotels</div>
                            </div>
                            <div class="kpi-value">
                                {{ $clothRow->cloth_count }}
                                <span>cloths</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="kpi-panel" data-kpi-panel="hotel">
        <div class="card" style="padding: 16px;">
            <div class="section-header" style="margin-bottom: 12px;">
                <span class="section-title">Hotel Wise</span>
            </div>

            @if($hotelWiseKpis->isEmpty())
                <div class="kpi-empty">No hotel breakdown available yet.</div>
            @else
                <div class="kpi-list">
                    @foreach($hotelWiseKpis as $hotelRow)
                        <div class="kpi-row">
                            <div>
                                <div class="kpi-label">{{ $hotelRow->name }}</div>
                                <div class="kpi-meta">{{ $hotelRow->collection_count }} collection records</div>
                            </div>
                            <div class="kpi-value">
                                {{ $hotelRow->cloth_count }}
                                <span>cloths collected</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="kpi-panel" data-kpi-panel="cloth">
        <div class="card" style="padding: 16px;">
            <div class="section-header" style="margin-bottom: 12px;">
                <span class="section-title">Cloth Type Wise</span>
            </div>

            @if($clothTypeWiseKpis->isEmpty())
                <div class="kpi-empty">No cloth type breakdown available yet.</div>
            @else
                <div class="kpi-list">
                    @foreach($clothTypeWiseKpis as $clothRow)
                        <div class="kpi-row">
                            <div>
                                <div class="kpi-label">{{ $clothRow->name }}</div>
                                <div class="kpi-meta">{{ $clothRow->hotel_count }} hotels collected this type</div>
                            </div>
                            <div class="kpi-value">
                                {{ $clothRow->cloth_count }}
                                <span>cloths collected</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('[data-kpi-tab]');
        const tabPanels = document.querySelectorAll('[data-kpi-panel]');

        function switchKpiTab(tabName) {
            tabButtons.forEach((button) => {
                button.classList.toggle('active', button.dataset.kpiTab === tabName);
            });

            tabPanels.forEach((panel) => {
                panel.classList.toggle('active', panel.dataset.kpiPanel === tabName);
            });
        }

        tabButtons.forEach((button) => {
            button.addEventListener('click', () => switchKpiTab(button.dataset.kpiTab));
        });
    </script>
</div>
@endsection
