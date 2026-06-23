<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <title>Login - Washtrack</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #0f172a;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Animated background */
        .bg-orbs {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .bg-orbs .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 8s infinite ease-in-out;
        }

        .bg-orbs .orb-1 {
            width: 300px; height: 300px;
            background: rgba(99, 102, 241, 0.2);
            top: -10%; right: -10%;
            animation-delay: 0s;
        }

        .bg-orbs .orb-2 {
            width: 250px; height: 250px;
            background: rgba(168, 85, 247, 0.15);
            bottom: -10%; left: -10%;
            animation-delay: 2s;
        }

        .bg-orbs .orb-3 {
            width: 200px; height: 200px;
            background: rgba(236, 72, 153, 0.1);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -20px) scale(1.05); }
            66% { transform: translate(-10px, 15px) scale(0.95); }
        }

        /* Login container */
        .login-container {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo h1 {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .login-logo p {
            font-size: 14px;
            color: #64748b;
        }

        /* Card */
        .login-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(51, 65, 85, 0.6);
            border-radius: 20px;
            padding: 28px 24px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
        }

        /* Tabs */
        .login-tabs {
            display: flex;
            background: #0f172a;
            border-radius: 14px;
            padding: 4px;
            margin-bottom: 28px;
        }

        .login-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            background: transparent;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            -webkit-tap-highlight-color: transparent;
        }

        .login-tab.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4);
        }

        /* Forms */
        .login-form {
            display: none;
        }

        .login-form.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 12px;
            color: #f1f5f9;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
            outline: none;
            -webkit-appearance: none;
        }

        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }

        .form-input::placeholder {
            color: #475569;
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .form-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        /* PIN Input */
        .pin-container {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .pin-input {
            width: 56px;
            height: 64px;
            background: #0f172a;
            border: 1.5px solid #334155;
            border-radius: 14px;
            color: #f1f5f9;
            font-size: 24px;
            font-weight: 700;
            font-family: inherit;
            text-align: center;
            outline: none;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            caret-color: #6366f1;
        }

        .pin-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
            transform: scale(1.05);
        }

        .pin-input.filled {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            -webkit-tap-highlight-color: transparent;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert */
        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Decorative divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #334155;
        }

        .divider span {
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <!-- Background orbs -->
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <div class="login-container">
        <!-- Logo -->
        <div class="login-logo">
            <img src="/images/logo.png" alt="Washtrack Logo" style="width: 120px; height: 120px; margin-bottom: 16px; border-radius: 24px; box-shadow: 0 8px 32px rgba(0,0,0,0.4); border: 2px solid rgba(99, 102, 241, 0.3);">
            {{-- <h1>WASHTRACK</h1> --}}
            <p>Laundry Management System</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <!-- Tabs -->
            <div class="login-tabs">
                <button class="login-tab" onclick="switchTab('superadmin')" type="button" id="tab-superadmin">Super Admin</button>
                <button class="login-tab active" onclick="switchTab('employee')" type="button" id="tab-employee">Partner</button>
            </div>

            <!-- SuperAdmin Form -->
            <form class="login-form" id="form-superadmin" action="{{ route('login.superadmin') }}" method="POST">
                @csrf

                @if($errors->has('email') && !($errors->has('contact_number') || $errors->has('password')))
                    <div class="alert alert-danger">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="sa-email">Email Address</label>
                    <input class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           type="email"
                           id="sa-email"
                           name="email"
                           placeholder="admin@delux.com"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           inputmode="email">
                </div>

                <div class="form-group">
                    <label class="form-label" for="sa-password">Password</label>
                    <input class="form-input"
                           type="password"
                           id="sa-password"
                           name="password"
                           placeholder="Enter your password"
                           required
                           autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <!-- Employee Form -->
            <form class="login-form active" id="form-employee" action="{{ route('login.employee') }}" method="POST">
                @csrf

                @if($errors->has('password') || (!session('errors') && !session('success')))
                    @if($errors->any() && $errors->has('password'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                @endif

                <div class="form-group" style="margin-top: 24px;">
                    <label class="form-label" style="text-align: center;">Enter 4-Digit PIN</label>
                    <div class="pin-container">
                        <input class="pin-input" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" data-pin="1" autocomplete="off">
                        <input class="pin-input" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" data-pin="2" autocomplete="off">
                        <input class="pin-input" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" data-pin="3" autocomplete="off">
                        <input class="pin-input" type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]" data-pin="4" autocomplete="off">
                    </div>
                    <input type="hidden" name="password" id="pin-hidden">
                </div>

                <button type="submit" class="btn-login" id="emp-submit">Sign In</button>
            </form>
        </div>
    </div>

    <script>
        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.login-form').forEach(f => f.classList.remove('active'));

            document.getElementById('tab-' + tab).classList.add('active');
            document.getElementById('form-' + tab).classList.add('active');
        }

        // Auto switch tab on validation error
        @if($errors->has('contact_number') || $errors->has('password'))
            switchTab('employee');
        @endif

        // PIN input handling
        const pinInputs = document.querySelectorAll('.pin-input');
        const pinHidden = document.getElementById('pin-hidden');

        function updateHiddenPin() {
            let pin = '';
            pinInputs.forEach(input => pin += input.value);
            pinHidden.value = pin;
        }

        pinInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val;

                if (val) {
                    e.target.classList.add('filled');
                    if (index < pinInputs.length - 1) {
                        pinInputs[index + 1].focus();
                    }
                } else {
                    e.target.classList.remove('filled');
                }
                updateHiddenPin();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    pinInputs[index - 1].focus();
                    pinInputs[index - 1].value = '';
                    pinInputs[index - 1].classList.remove('filled');
                    updateHiddenPin();
                }
            });

            // Handle paste
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                for (let i = 0; i < 4 && i < paste.length; i++) {
                    pinInputs[i].value = paste[i];
                    pinInputs[i].classList.add('filled');
                }
                if (paste.length > 0) {
                    pinInputs[Math.min(paste.length, 3)].focus();
                }
                updateHiddenPin();
            });

            // Select on focus
            input.addEventListener('focus', () => {
                input.select();
            });
        });
    </script>
</body>
</html>
