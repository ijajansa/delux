@extends('layouts.app')

@section('title', 'Add Employee - Delux Admin')

@section('content')
<div class="animate-in">
    <!-- Back button -->
    <a href="{{ route('employees.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--accent);font-size:14px;font-weight:600;text-decoration:none;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
        Back
    </a>

    <h1 class="page-title">Add Employee</h1>
    <p class="page-subtitle">Register a new team member</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
            Please fix the errors below
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom: 20px;">
            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       type="text"
                       id="name"
                       name="name"
                       placeholder="Enter employee name"
                       value="{{ old('name') }}"
                       required
                       autocomplete="name">
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
                       value="{{ old('contact_number') }}"
                       maxlength="10"
                       pattern="[0-9]{10}"
                       required
                       inputmode="tel">
                @error('contact_number')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="form-hint">Keep this for contact and record-keeping.</p>
            </div>

            <div class="form-group">
                <label class="form-label" for="login_password">Partner Password</label>
                <input class="form-input {{ $errors->has('login_password') ? 'is-invalid' : '' }}"
                       type="tel"
                       id="login_password"
                       name="login_password"
                       placeholder="Set 4-digit partner PIN"
                       maxlength="4"
                       pattern="[0-9]{4}"
                       required
                       inputmode="numeric"
                       autocomplete="new-password"
                       style="letter-spacing: 8px; text-align: center; font-size: 20px; font-weight: 700;">
                @error('login_password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="login_password_confirmation">Confirm PIN</label>
                <input class="form-input"
                       type="tel"
                       id="login_password_confirmation"
                       name="login_password_confirmation"
                       placeholder="Confirm 4-digit PIN"
                       maxlength="4"
                       pattern="[0-9]{4}"
                       required
                       inputmode="numeric"
                       autocomplete="new-password"
                       style="letter-spacing: 8px; text-align: center; font-size: 20px; font-weight: 700;">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="height: 54px; font-size: 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" /></svg>
            Register Employee
        </button>
    </form>
</div>
@endsection
