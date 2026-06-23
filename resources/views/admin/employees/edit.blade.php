@extends('layouts.app')

@section('title', 'Edit Employee - Delux Admin')

@section('content')
<div class="animate-in">
    <a href="{{ route('employees.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--accent);font-size:14px;font-weight:600;text-decoration:none;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
        Back
    </a>

    <h1 class="page-title">Edit Employee</h1>
    <p class="page-subtitle">Update {{ $employee->name }}'s details</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
            Please fix the errors below
        </div>
    @endif

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card" style="margin-bottom: 20px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:white;flex-shrink:0;">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:16px;font-weight:600;">{{ $employee->name }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);">📱 {{ $employee->contact_number }}</div>
                    <div style="font-size:13px;color:var(--text-secondary);">🔐 {{ $employee->login_password ?? 'Not set' }}</div>
                </div>
                <span class="badge {{ $employee->is_active ? 'badge-success' : 'badge-danger' }}" style="margin-left:auto;">
                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       type="text"
                       id="name"
                       name="name"
                       placeholder="Enter employee name"
                       value="{{ old('name', $employee->name) }}"
                       required>
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_number">Contact Number</label>
                <input class="form-input {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                       type="tel"
                       id="contact_number"
                       name="contact_number"
                       placeholder="10-digit mobile number"
                       value="{{ old('contact_number', $employee->contact_number) }}"
                       maxlength="10"
                       pattern="[0-9]{10}"
                       required
                       inputmode="tel">
                @error('contact_number')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--warning)" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                <span style="font-size:14px;font-weight:700;">Reset Password</span>
                <span style="font-size:12px;color:var(--text-muted);margin-left:auto;">Optional</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="login_password">New Partner Password</label>
                <input class="form-input {{ $errors->has('login_password') ? 'is-invalid' : '' }}"
                       type="tel"
                       id="login_password"
                       name="login_password"
                       placeholder="Leave blank to keep current"
                       maxlength="4"
                       pattern="[0-9]{4}"
                       inputmode="numeric"
                       style="letter-spacing: 8px; text-align: center; font-size: 20px; font-weight: 700;">
                @error('login_password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="login_password_confirmation">Confirm New PIN</label>
                <input class="form-input"
                       type="tel"
                       id="login_password_confirmation"
                       name="login_password_confirmation"
                       placeholder="Confirm new PIN"
                       maxlength="4"
                       pattern="[0-9]{4}"
                       inputmode="numeric"
                       style="letter-spacing: 8px; text-align: center; font-size: 20px; font-weight: 700;">
            </div>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary btn-full" style="height: 54px; font-size: 16px; flex: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                Save Changes
            </button>
        </div>
    </form>

    <form action="{{ route('employees.toggle', $employee->id) }}" method="POST" style="margin-top: 12px;">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn {{ $employee->is_active ? 'btn-danger' : 'btn-success' }} btn-full" style="height: 50px;">
            {{ $employee->is_active ? '⛔ Deactivate Employee' : '✅ Activate Employee' }}
        </button>
    </form>
</div>
@endsection
