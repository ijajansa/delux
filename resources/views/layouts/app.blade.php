<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Washtrack">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <title>@yield('title', 'Washtrack admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --bg-input: #0f172a;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent: #6366f1;
            --accent-hover: #818cf8;
            --accent-glow: rgba(99, 102, 241, 0.3);
            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.15);
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.15);
            --warning: #f59e0b;
            --warning-bg: rgba(245, 158, 11, 0.15);
            --border: #334155;
            --radius: 16px;
            --radius-sm: 12px;
            --shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 8px 40px rgba(0, 0, 0, 0.4);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            min-height: 100dvh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* Main content wrapper */
        .app-wrapper {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        /* Top header */
        .app-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .app-header .logo {
            font-size: 20px;
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .app-header .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: var(--bg-secondary);
            border-radius: 100px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .app-header .user-badge .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
        }

        /* Page content */
        .page-content {
            flex: 1;
            padding: 20px;
            padding-bottom: calc(80px + var(--safe-bottom));
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-around;
            padding: 8px 16px;
            padding-bottom: calc(8px + var(--safe-bottom));
        }

        .bottom-nav a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 500;
            border-radius: 12px;
            transition: var(--transition);
            min-width: 60px;
        }

        .bottom-nav a.active {
            color: var(--accent);
            background: var(--accent-glow);
        }

        .bottom-nav a:hover {
            color: var(--accent-hover);
        }

        .bottom-nav svg {
            width: 22px;
            height: 22px;
        }

        /* Page title */
        .page-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 24px;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            transition: var(--transition);
        }

        .card:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Stat cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 16px;
            text-align: center;
            transition: var(--transition);
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-card.total .stat-value { color: var(--accent); }
        .stat-card.active .stat-value { color: var(--success); }
        .stat-card.inactive .stat-value { color: var(--danger); }

        /* Form elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 16px;
            font-family: inherit;
            transition: var(--transition);
            outline: none;
            -webkit-appearance: none;
        }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px var(--danger-bg);
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        .form-hint {
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 6px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(99, 102, 241, 0.5);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1.5px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--accent);
            background: var(--bg-card-hover);
        }

        .btn-danger {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-success {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .btn-full {
            width: 100%;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 8px;
        }

        /* FAB */
        .fab {
            position: fixed;
            bottom: calc(76px + var(--safe-bottom));
            right: 20px;
            z-index: 90;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.5);
            transition: var(--transition);
            text-decoration: none;
        }

        .fab:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.6);
        }

        .fab:active {
            transform: scale(0.95);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: var(--success-bg);
            color: var(--success);
        }

        .badge-danger {
            background: var(--danger-bg);
            color: var(--danger);
        }

        /* Employee card */
        .employee-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
        }

        .employee-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-1px);
        }

        .employee-card .emp-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .employee-card .emp-info {
            flex: 1;
            min-width: 0;
        }

        .employee-card .emp-name {
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .employee-card .emp-contact {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .employee-card .emp-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Search bar */
        .search-bar {
            position: relative;
            margin-bottom: 20px;
        }

        .search-bar svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
        }

        .search-bar input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: var(--bg-card);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 15px;
            font-family: inherit;
            transition: var(--transition);
            outline: none;
        }

        .search-bar input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        /* Filter tabs */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .filter-tabs::-webkit-scrollbar { display: none; }

        .filter-tab {
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
            font-family: inherit;
        }

        .filter-tab.active {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .filter-tab:hover:not(.active) {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Alert messages */
        .alert {
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Section header */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .section-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Animations */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-in {
            animation: slideDown 0.4s ease;
        }

        /* Pagination */
        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .pagination-wrap a, .pagination-wrap span {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination-wrap a {
            background: var(--bg-card);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .pagination-wrap a:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination-wrap .current {
            background: var(--accent);
            color: white;
        }

        /* Responsive adjustments */
        @media (min-width: 768px) {
            .page-content {
                max-width: 640px;
                padding: 32px 24px;
            }
        }

        /* Dark gradient orbs (decorative) */
        body::before {
            content: '';
            position: fixed;
            top: -30%;
            right: -30%;
            width: 60%;
            height: 60%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            left: -20%;
            width: 50%;
            height: 50%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .app-wrapper { position: relative; z-index: 1; }
    </style>
    @yield('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- Header -->
        <header class="app-header">
            <div class="logo">WASHTRACK</div>
            @auth
            <div class="user-badge">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
            @endauth
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>

        <!-- Bottom Navigation -->
        @auth
        <nav class="bottom-nav">
            @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                    <span>Items</span>
                </a>
                <a href="{{ route('hotels.index') }}" class="{{ request()->routeIs('hotels.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-3h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12" /></svg>
                    <span>Hotels</span>
                </a>
            @else
                <a href="{{ route('employee.dashboard') }}" class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    <span>Home</span>
                </a>
                <a href="{{ route('collections.create') }}" class="{{ request()->routeIs('collections.create', 'collections.entry') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <span>Collect</span>
                </a>
                <a href="{{ route('collections.history') }}" class="{{ request()->routeIs('collections.history') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    <span>History</span>
                </a>
            @endif
            
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" /></svg>
                <span>Logout</span>
            </a>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        @endauth
    </div>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('sw.js') }}', { scope: '{{ url('/') }}/' })
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.log('SW failed:', err));
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
