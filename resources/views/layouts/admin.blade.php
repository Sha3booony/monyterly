<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Monitorly')</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg-primary: #08080d; --bg-secondary: #0e0e16; --bg-card: #14141e;
            --bg-card-hover: #1a1a28; --border: #252535; --text-primary: #e8e8ef;
            --text-secondary: #8888aa; --text-muted: #55556a;
            --accent: #ff6b2b; --accent-dim: #e55a1b; --accent-glow: #ff6b2b33;
            --green: #00ff88; --red: #ff3366; --yellow: #ffaa00; --blue: #00aaff;
            --purple: #aa55ff;
            --gradient-admin: linear-gradient(135deg, #ff6b2b, #ff3366);
            --font-display: 'Orbitron', sans-serif; --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --radius: 12px; --radius-sm: 8px; --radius-lg: 16px;
            --sidebar-width: 260px;
        }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--bg-primary); color: var(--text-primary); line-height: 1.6; -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        .sidebar {
            position: fixed; top: 0; {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
            width: var(--sidebar-width); height: 100vh;
            background: var(--bg-secondary); border-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 50;
        }
        .sidebar-logo { padding: 24px; border-bottom: 1px solid var(--border); }
        .sidebar-logo a {
            font-family: var(--font-display); font-size: 1.1rem; font-weight: 800;
            text-decoration: none; background: var(--gradient-admin);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; letter-spacing: 2px;
        }
        .sidebar-logo .admin-badge {
            display: inline-block; font-family: var(--font-mono); font-size: 0.65rem;
            padding: 2px 8px; border-radius: 4px; background: rgba(255,107,43,0.15);
            color: var(--accent); border: 1px solid rgba(255,107,43,0.3);
            margin-top: 4px; letter-spacing: 1px;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; list-style: none; overflow-y: auto; }
        .sidebar-nav li { margin-bottom: 4px; }
        .sidebar-section { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); padding: 16px 16px 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 11px 16px;
            border-radius: var(--radius-sm); color: var(--text-secondary);
            text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s;
        }
        .sidebar-link:hover { background: var(--bg-card); color: var(--text-primary); }
        .sidebar-link.active {
            background: rgba(255,107,43,0.1); color: var(--accent);
            border: 1px solid rgba(255,107,43,0.2);
        }
        .sidebar-link .icon { font-size: 1.1rem; width: 24px; text-align: center; }
        .sidebar-link .count {
            margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: auto;
            font-family: var(--font-mono); font-size: 0.75rem;
            padding: 2px 8px; border-radius: 10px; background: var(--bg-card);
            color: var(--text-muted);
        }

        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); }
        .sidebar-footer a {
            display: flex; align-items: center; gap: 8px; padding: 10px 16px;
            border-radius: var(--radius-sm); color: var(--text-secondary);
            text-decoration: none; font-size: 0.85rem; transition: all 0.2s;
            border: 1px solid var(--border);
        }
        .sidebar-footer a:hover { border-color: var(--green); color: var(--green); }

        .main { {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: var(--sidebar-width); min-height: 100vh; }
        .topbar {
            position: sticky; top: 0; z-index: 40; padding: 16px 32px;
            background: rgba(8,8,13,0.9); backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title { font-family: var(--font-display); font-size: 1rem; font-weight: 700; letter-spacing: 1px; }
        .content { padding: 32px; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; border-radius: var(--radius); font-family: var(--font-body); font-weight: 600; font-size: 0.9rem; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: var(--gradient-admin); color: #fff; }
        .btn-primary:hover { box-shadow: 0 0 30px var(--accent-glow); transform: translateY(-1px); }
        .btn-secondary { background: transparent; color: var(--text-primary); border: 1px solid var(--border); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: var(--red); color: #fff; }
        .btn-sm { padding: 6px 14px; font-size: 0.8rem; }
        .btn-xs { padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; }

        /* Cards */
        .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 24px; transition: all 0.3s; }
        .card:hover { border-color: rgba(255,107,43,0.15); }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 20px; position: relative; overflow: hidden; }
        .stat-card::after { content:''; position:absolute; top:0; {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}:0; width:3px; height:100%; }
        .stat-card.orange::after { background: var(--accent); }
        .stat-card.green::after { background: var(--green); }
        .stat-card.red::after { background: var(--red); }
        .stat-card.blue::after { background: var(--blue); }
        .stat-card.purple::after { background: var(--purple); }
        .stat-card.yellow::after { background: var(--yellow); }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .stat-value { font-family: var(--font-display); font-size: 1.8rem; font-weight: 800; }
        .stat-card.orange .stat-value { color: var(--accent); }
        .stat-card.green .stat-value { color: var(--green); }
        .stat-card.red .stat-value { color: var(--red); }
        .stat-card.blue .stat-value { color: var(--blue); }
        .stat-card.purple .stat-value { color: var(--purple); }
        .stat-card.yellow .stat-value { color: var(--yellow); }

        /* Table */
        .table-wrap { overflow-x: auto; border-radius: var(--radius-lg); border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 12px 16px; text-align: start; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); background: var(--bg-secondary); border-bottom: 1px solid var(--border); }
        td { padding: 12px 16px; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-card-hover); }

        /* Badges */
        .badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.3px; }
        .badge-up { background:rgba(0,255,136,0.1); color:#00ff88; }
        .badge-down { background:rgba(255,51,102,0.1); color:#ff3366; }
        .badge-pending { background:rgba(255,170,0,0.1); color:#ffaa00; }
        .badge-paused { background:rgba(136,136,170,0.1); color:#8888aa; }
        .badge-admin { background:rgba(255,107,43,0.15); color:var(--accent); }
        .badge-user { background:rgba(0,170,255,0.1); color:var(--blue); }
        .badge-open { background:rgba(255,51,102,0.1); color:#ff3366; }
        .badge-acknowledged { background:rgba(255,170,0,0.1); color:#ffaa00; }
        .badge-resolved { background:rgba(0,255,136,0.1); color:#00ff88; }
        .badge-closed { background:rgba(136,136,170,0.1); color:#8888aa; }
        .badge-critical { background:rgba(255,51,102,0.15); color:#ff3366; }
        .badge-high { background:rgba(255,107,43,0.15); color:#ff6b2b; }
        .badge-medium { background:rgba(255,170,0,0.15); color:#ffaa00; }
        .badge-low { background:rgba(0,170,255,0.15); color:#00aaff; }

        /* Alert */
        .alert { padding: 12px 18px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 0.85rem; }
        .alert-success { background: rgba(0,255,136,0.08); border: 1px solid rgba(0,255,136,0.2); color: var(--green); }
        .alert-error { background: rgba(255,51,102,0.08); border: 1px solid rgba(255,51,102,0.2); color: var(--red); }

        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-family: var(--font-display); font-size: 0.95rem; font-weight: 600; letter-spacing: 0.5px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .empty { text-align: center; padding: 40px; color: var(--text-muted); }

        .pagination { display:flex; gap:6px; justify-content:center; margin-top:20px; list-style:none; }
        .pagination a, .pagination span { padding:6px 12px; background:var(--bg-card); border:1px solid var(--border); border-radius:6px; color:var(--text-secondary); text-decoration:none; font-size:0.85rem; }
        .pagination a:hover { border-color:var(--accent); color:var(--accent); }
        .pagination .active span { background:var(--accent); color:#fff; border-color:var(--accent); }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin: 0 !important; }
            .grid-2 { grid-template-columns: 1fr; }
            .content { padding: 16px; }
        }

        @yield('styles')
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}">MONITORLY</a>
            <div><span class="admin-badge">⚡ ADMIN PANEL</span></div>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-section">Main</li>
            <li><a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="icon">🏠</span> Overview</a></li>

            <li class="sidebar-section">Management</li>
            <li><a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') || request()->routeIs('admin.user-detail') ? 'active' : '' }}"><span class="icon">👥</span> Users</a></li>
            <li><a href="{{ route('admin.monitors') }}" class="sidebar-link {{ request()->routeIs('admin.monitors') ? 'active' : '' }}"><span class="icon">📡</span> All Monitors</a></li>
            <li><a href="{{ route('admin.issues') }}" class="sidebar-link {{ request()->routeIs('admin.issues') ? 'active' : '' }}"><span class="icon">🎫</span> All Issues</a></li>
            <li><a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}"><span class="icon">📋</span> System Logs</a></li>

            <li class="sidebar-section">Quick Links</li>
            <li><a href="{{ route('dashboard') }}" class="sidebar-link"><span class="icon">📊</span> User Dashboard</a></li>
            <li><a href="{{ route('landing') }}" class="sidebar-link"><span class="icon">🌐</span> Landing Page</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1 class="topbar-title">@yield('page-title', 'Admin Panel')</h1>
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:0.85rem;color:var(--text-muted);">{{ auth()->user()->name }}</span>
                <span class="badge badge-admin">ADMIN</span>
            </div>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">⚠️ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
