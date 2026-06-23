@extends('layouts.app')

@section('title', 'Employee Dashboard - Delux')

@section('content')
<div class="animate-in">
    <h1 class="page-title">Welcome! 👋</h1>
    <p class="page-subtitle">{{ Auth::user()->name }}</p>

    <!-- Profile Card -->
    <div class="card" style="margin-bottom: 24px; text-align: center; padding: 32px 20px;">
        <div style="width: 80px; height: 80px; border-radius: 24px; background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; color: white; margin: 0 auto 16px;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 4px;">{{ Auth::user()->name }}</h2>
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 16px;">Employee</p>

        <div style="display: flex; justify-content: center; gap: 24px; padding-top: 16px; border-top: 1px solid var(--border); flex-direction: column;">
            <a href="{{ route('collections.create') }}" class="btn btn-primary btn-full" style="margin-bottom: 8px;">Start Collection</a>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <a href="{{ route('hotels.index') }}" class="btn btn-secondary btn-sm">Manage Hotels</a>
                <a href="{{ route('cloth-types.index') }}" class="btn btn-secondary btn-sm">Cloth Types</a>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(99, 102, 241, 0.15); display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--accent)" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
            </div>
            <span style="font-size: 15px; font-weight: 600;">About</span>
        </div>
        <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">
            You're logged in as an employee. Your admin manages your account access. If you have any questions, please contact your supervisor.
        </p>
    </div>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST" style="margin-top: 24px;">
        @csrf
        <button type="submit" class="btn btn-secondary btn-full" style="height: 50px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" /></svg>
            Sign Out
        </button>
    </form>
</div>
@endsection
