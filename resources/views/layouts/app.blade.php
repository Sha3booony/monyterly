<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Monitorly - Monitor your websites 24/7 with instant alerts, issue tracking, and uptime reports.">
    <title>@yield('title', 'Monitorly — Website Monitoring')</title>
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
            --border-glow: #00ff8855;
            --text-primary: #e8e8ef;
            --text-secondary: #8888aa;
            --text-muted: #55556a;
            --accent: #00ff88;
            --accent-dim: #00cc6a;
            --accent-glow: #00ff8833;
            --danger: #ff3366;
            --danger-glow: #ff336633;
            --warning: #ffaa00;
            --warning-glow: #ffaa0033;
            --info: #00aaff;
            --info-glow: #00aaff33;
            --gradient-1: linear-gradient(135deg, #00ff88, #00aaff);
            --gradient-2: linear-gradient(135deg, #ff3366, #ff6b2b);
            --gradient-3: linear-gradient(135deg, #7c3aed, #00aaff);
            --font-display: 'Orbitron', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
            --shadow-glow: 0 0 20px rgba(0, 255, 136, 0.15);
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

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-dim); }

        /* Utility Classes */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .text-accent { color: var(--accent); }
        .text-danger { color: var(--danger); }
        .text-warning { color: var(--warning); }
        .text-muted { color: var(--text-secondary); }
        .text-center { text-align: center; }
        .font-display { font-family: var(--font-display); }
        .font-mono { font-family: var(--font-mono); }

        /* Buttons */
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: #000;
            box-shadow: 0 0 20px var(--accent-glow);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px var(--accent-glow), 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover {
            box-shadow: 0 0 20px var(--danger-glow);
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: all 0.3s ease;
        }
        .card:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow-glow);
        }

        /* Forms */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-error { color: var(--danger); font-size: 0.85rem; margin-top: 6px; }
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-up { background: rgba(0, 255, 136, 0.12); color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.3); }
        .badge-down { background: rgba(255, 51, 102, 0.12); color: #ff3366; border: 1px solid rgba(255, 51, 102, 0.3); }
        .badge-pending { background: rgba(255, 170, 0, 0.12); color: #ffaa00; border: 1px solid rgba(255, 170, 0, 0.3); }
        .badge-paused { background: rgba(136, 136, 170, 0.12); color: #8888aa; border: 1px solid rgba(136, 136, 170, 0.3); }
        .badge-open { background: rgba(255, 51, 102, 0.12); color: #ff3366; }
        .badge-acknowledged { background: rgba(255, 170, 0, 0.12); color: #ffaa00; }
        .badge-resolved { background: rgba(0, 255, 136, 0.12); color: #00ff88; }
        .badge-closed { background: rgba(136, 136, 170, 0.12); color: #8888aa; }
        .badge-critical { background: rgba(255, 51, 102, 0.2); color: #ff3366; }
        .badge-high { background: rgba(255, 107, 43, 0.2); color: #ff6b2b; }
        .badge-medium { background: rgba(255, 170, 0, 0.2); color: #ffaa00; }
        .badge-low { background: rgba(0, 170, 255, 0.2); color: #00aaff; }

        /* Badge pulse */
        .badge-up::before, .badge-down::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .badge-up::before { background: #00ff88; box-shadow: 0 0 8px #00ff88; animation: pulse-green 2s infinite; }
        .badge-down::before { background: #ff3366; box-shadow: 0 0 8px #ff3366; animation: pulse-red 1s infinite; }

        @keyframes pulse-green {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        @keyframes pulse-red {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Table */
        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            padding: 14px 18px;
            text-align: start;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-card-hover); }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px var(--accent-glow); }
            50% { box-shadow: 0 0 40px var(--accent-glow); }
        }
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        .animate-fade-up {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }

        /* Alert Messages */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--accent);
        }
        .alert-error {
            background: rgba(255, 51, 102, 0.1);
            border: 1px solid rgba(255, 51, 102, 0.3);
            color: var(--danger);
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 24px;
            list-style: none;
        }
        .pagination a, .pagination span {
            padding: 8px 14px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .pagination a:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .pagination .active span {
            background: var(--accent);
            color: #000;
            border-color: var(--accent);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 0 16px; }
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
    @yield('scripts')
</body>
</html>
