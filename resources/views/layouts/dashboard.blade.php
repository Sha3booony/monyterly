<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard — Monitorly')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #111118;
            --bg-card: #16161f;
            --bg-card-hover: #1c1c28;
            --border: #2a2a3a;
            --text-primary: #e8e8ef;
            --text-secondary: #8888aa;
            --text-muted: #55556a;
            --accent: #00ff88;
            --accent-dim: #00cc6a;
            --accent-glow: #00ff8833;
            --danger: #ff3366;
            --danger-glow: #ff336633;
            --warning: #ffaa00;
            --info: #00aaff;
            --gradient-1: linear-gradient(135deg, #00ff88, #00aaff);
            --font-display: 'Orbitron', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
            --shadow-glow: 0 0 20px rgba(0, 255, 136, 0.15);
            --sidebar-width: 260px;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        [dir="rtl"] body { direction: rtl; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* ═══ SIDEBAR ═══ */
        .sidebar {
            position: fixed;
            top: 0;
            {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-secondary);
            border-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .sidebar-logo {
            padding: 24px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo a {
            font-family: var(--font-display);
            font-size: 1.2rem;
            font-weight: 800;
            text-decoration: none;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            list-style: none;
        }
        .sidebar-nav li { margin-bottom: 4px; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover {
            background: var(--bg-card);
            color: var(--text-primary);
        }
        .sidebar-link.active {
            background: rgba(0, 255, 136, 0.08);
            color: var(--accent);
            border: 1px solid rgba(0, 255, 136, 0.15);
        }
        .sidebar-link .icon { font-size: 1.2rem; width: 24px; text-align: center; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .sidebar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #000;
            font-size: 0.9rem;
        }
        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }
        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-email {
            font-size: 0.8rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-actions {
            display: flex;
            gap: 8px;
        }
        .sidebar-actions a, .sidebar-actions button {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            font-family: var(--font-body);
        }
        .sidebar-actions a:hover, .sidebar-actions button:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ═══ MAIN CONTENT ═══ */
        .main {
            {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            padding: 16px 32px;
            background: rgba(10, 10, 15, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-title {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .content {
            padding: 32px;
        }

        /* ═══ Utility ═══ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: var(--radius);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: var(--gradient-1);
            color: #000;
            box-shadow: 0 0 20px var(--accent-glow);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 40px var(--accent-glow); }
        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { box-shadow: 0 0 20px var(--danger-glow); }
        .btn-sm { padding: 8px 16px; font-size: 0.85rem; }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: all 0.3s ease;
        }
        .card:hover {
            border-color: rgba(0, 255, 136, 0.2);
        }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-secondary); font-size: 0.9rem; }
        .form-input {
            width: 100%; padding: 12px 16px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
        .form-input::placeholder { color: var(--text-muted); }
        .form-error { color: var(--danger); font-size: 0.85rem; margin-top: 6px; }
        .form-check { display: flex; align-items: center; gap: 10px; }
        .form-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--accent); }
        select.form-input { cursor: pointer; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badge-up { background: rgba(0,255,136,0.12); color: #00ff88; border: 1px solid rgba(0,255,136,0.3); }
        .badge-down { background: rgba(255,51,102,0.12); color: #ff3366; border: 1px solid rgba(255,51,102,0.3); }
        .badge-pending { background: rgba(255,170,0,0.12); color: #ffaa00; border: 1px solid rgba(255,170,0,0.3); }
        .badge-paused { background: rgba(136,136,170,0.12); color: #8888aa; border: 1px solid rgba(136,136,170,0.3); }
        .badge-open { background: rgba(255,51,102,0.12); color: #ff3366; }
        .badge-acknowledged { background: rgba(255,170,0,0.12); color: #ffaa00; }
        .badge-resolved { background: rgba(0,255,136,0.12); color: #00ff88; }
        .badge-closed { background: rgba(136,136,170,0.12); color: #8888aa; }
        .badge-critical { background: rgba(255,51,102,0.2); color: #ff3366; }
        .badge-high { background: rgba(255,107,43,0.2); color: #ff6b2b; }
        .badge-medium { background: rgba(255,170,0,0.2); color: #ffaa00; }
        .badge-low { background: rgba(0,170,255,0.2); color: #00aaff; }
        .badge-up::before, .badge-down::before {
            content: ''; width: 8px; height: 8px;
            border-radius: 50%; display: inline-block;
        }
        .badge-up::before { background: #00ff88; box-shadow: 0 0 8px #00ff88; animation: pulse-green 2s infinite; }
        .badge-down::before { background: #ff3366; box-shadow: 0 0 8px #ff3366; animation: pulse-red 1s infinite; }

        @keyframes pulse-green { 0%,100%{opacity:1} 50%{opacity:0.4} }
        @keyframes pulse-red { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Table */
        .table-wrap { overflow-x: auto; border-radius: var(--radius-lg); border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 14px 18px; text-align: start; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); background: var(--bg-secondary); border-bottom: 1px solid var(--border); }
        td { padding: 14px 18px; border-bottom: 1px solid var(--border); font-size: 0.95rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-card-hover); }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        .stat-card:hover { border-color: rgba(0,255,136,0.2); }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 0;
            width: 4px;
            height: 100%;
        }
        .stat-card.green::after { background: #00ff88; box-shadow: 0 0 10px #00ff8855; }
        .stat-card.red::after { background: #ff3366; box-shadow: 0 0 10px #ff336655; }
        .stat-card.blue::after { background: #00aaff; box-shadow: 0 0 10px #00aaff55; }
        .stat-card.yellow::after { background: #ffaa00; box-shadow: 0 0 10px #ffaa0055; }
        .stat-label { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-family: var(--font-display); font-size: 2rem; font-weight: 800; }
        .stat-card.green .stat-value { color: #00ff88; }
        .stat-card.red .stat-value { color: #ff3366; }
        .stat-card.blue .stat-value { color: #00aaff; }
        .stat-card.yellow .stat-value { color: #ffaa00; }

        /* Alert */
        .alert { padding: 14px 20px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.9rem; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: rgba(0,255,136,0.1); border: 1px solid rgba(0,255,136,0.3); color: var(--accent); }
        .alert-error { background: rgba(255,51,102,0.1); border: 1px solid rgba(255,51,102,0.3); color: var(--danger); }

        /* Pagination */
        .pagination { display: flex; gap: 8px; justify-content: center; margin-top: 24px; list-style: none; }
        .pagination a, .pagination span { padding: 8px 14px; background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: all 0.3s ease; }
        .pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .pagination .active span { background: var(--accent); color: #000; border-color: var(--accent); }

        .empty-state { text-align: center; padding: 60px 24px; color: var(--text-secondary); }
        .empty-state .icon { font-size: 3rem; margin-bottom: 16px; opacity: 0.5; }
        .empty-state p { margin-bottom: 24px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-family: var(--font-display); font-size: 1rem; font-weight: 600; letter-spacing: 0.5px; }

        /* Hamburger for mobile */
        .hamburger { display: none; background: none; border: none; color: var(--text-primary); font-size: 1.5rem; cursor: pointer; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX({{ app()->getLocale() === 'ar' ? '100%' : '-100%' }}); }
            .sidebar.open { transform: translateX(0); }
            .main { {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: 0; }
            .hamburger { display: block; }
            .content { padding: 20px; }
            .grid-2 { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('dashboard') }}">MONITORLY</a>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon">📊</span> {{ __('messages.overview') }}
                </a>
            </li>
            <li>
                <a href="{{ route('monitors.index') }}" class="sidebar-link {{ request()->routeIs('monitors.*') ? 'active' : '' }}">
                    <span class="icon">📡</span> {{ __('messages.monitors') }}
                </a>
            </li>
            <li>
                <a href="{{ route('issues.index') }}" class="sidebar-link {{ request()->routeIs('issues.*') ? 'active' : '' }}">
                    <span class="icon">🎫</span> {{ __('messages.issues') }}
                </a>
            </li>
            @if(auth()->user()->is_admin)
            <li style="margin-top:16px;padding-top:12px;border-top:1px solid var(--border);">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link" style="color:#ff6b2b;">
                    <span class="icon">⚡</span> Admin Panel
                </a>
            </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="sidebar-actions">
                @if(app()->getLocale() === 'ar')
                    <a href="{{ route('lang.switch', 'en') }}">🌐 EN</a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}">🌐 عر</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="flex:1;display:flex;">
                    @csrf
                    <button type="submit" style="flex:1;">🚪 {{ __('messages.logout') }}</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <div class="topbar">
            <div style="display:flex;align-items:center;gap:16px;">
                <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
                <h1 class="topbar-title">@yield('page-title', __('messages.dashboard'))</h1>
            </div>
            <div class="topbar-right">
                @yield('topbar-actions')
            </div>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        ⚠️ {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>
