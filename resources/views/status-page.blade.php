<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} — Status Page | Monitorly</title>
    <meta name="description" content="Live status page for {{ $user->name }}'s monitored services">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0f; --bg2: #111118; --bg3: #16161f; --border: #2a2a3a;
            --text: #e8e8ef; --text2: #8888aa; --text3: #55556a;
            --green: #00ff88; --red: #ff3366; --yellow: #ffaa00; --blue: #00aaff;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        .container { max-width: 800px; margin: 0 auto; padding: 40px 20px; }

        .header { text-align: center; margin-bottom: 48px; }
        .header .logo { font-family: 'Orbitron', sans-serif; font-size: 1.4rem; font-weight: 800; background: linear-gradient(135deg, #00ff88, #00aaff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 3px; margin-bottom: 8px; }
        .header .subtitle { color: var(--text2); font-size: 0.95rem; }

        /* Overall Status */
        .overall {
            text-align: center; padding: 32px; border-radius: 16px; margin-bottom: 32px;
            border: 1px solid var(--border);
        }
        .overall.all-up { background: rgba(0,255,136,0.06); border-color: rgba(0,255,136,0.2); }
        .overall.some-down { background: rgba(255,51,102,0.06); border-color: rgba(255,51,102,0.2); }

        .overall-icon { font-size: 2.5rem; margin-bottom: 12px; }
        .overall-text { font-family: 'Orbitron', sans-serif; font-size: 1.3rem; font-weight: 700; letter-spacing: 1px; }
        .overall.all-up .overall-text { color: var(--green); }
        .overall.some-down .overall-text { color: var(--red); }
        .overall-sub { color: var(--text2); font-size: 0.85rem; margin-top: 8px; }

        .uptime-summary { display: flex; justify-content: center; gap: 32px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border); }
        .uptime-item { text-align: center; }
        .uptime-num { font-family: 'Orbitron', sans-serif; font-size: 1.6rem; font-weight: 700; }
        .uptime-label { font-size: 0.75rem; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }

        /* Monitor List */
        .monitor-list { display: flex; flex-direction: column; gap: 12px; }
        .monitor-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px; background: var(--bg3); border: 1px solid var(--border);
            border-radius: 12px; transition: all 0.3s;
        }
        .monitor-item:hover { border-color: rgba(0,255,136,0.15); }
        .monitor-left { display: flex; align-items: center; gap: 14px; }
        .status-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
        .status-dot.up { background: var(--green); box-shadow: 0 0 10px rgba(0,255,136,0.5); animation: pulse 2s infinite; }
        .status-dot.down { background: var(--red); box-shadow: 0 0 10px rgba(255,51,102,0.5); animation: pulse-r 1s infinite; }
        .status-dot.pending { background: var(--yellow); }
        .status-dot.paused { background: var(--text3); }
        @keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(0,255,136,0.4)} 50%{box-shadow:0 0 0 8px rgba(0,255,136,0)} }
        @keyframes pulse-r { 0%,100%{box-shadow:0 0 0 0 rgba(255,51,102,0.4)} 50%{box-shadow:0 0 0 8px rgba(255,51,102,0)} }

        .monitor-name { font-weight: 600; font-size: 0.95rem; }
        .monitor-url { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text3); margin-top: 2px; }

        .monitor-right { text-align: end; }
        .monitor-uptime { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 0.95rem; }
        .monitor-response { font-size: 0.8rem; color: var(--text3); margin-top: 2px; font-family: 'JetBrains Mono', monospace; }
        .monitor-checked { font-size: 0.75rem; color: var(--text3); margin-top: 4px; }

        .uptime-bar { width: 100%; height: 6px; background: rgba(255,51,102,0.15); border-radius: 3px; margin-top: 10px; overflow: hidden; }
        .uptime-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--green), var(--blue)); }

        .footer { text-align: center; margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--border); }
        .footer a { color: var(--green); text-decoration: none; font-family: 'Orbitron', sans-serif; font-weight: 600; font-size: 0.85rem; letter-spacing: 2px; }
        .footer .time { color: var(--text3); font-size: 0.8rem; margin-top: 8px; font-family: 'JetBrains Mono', monospace; }

        .badge-status { display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;text-transform:uppercase; }
        .badge-status.up { background:rgba(0,255,136,0.12);color:var(--green); }
        .badge-status.down { background:rgba(255,51,102,0.12);color:var(--red); }

        @media (max-width:600px) {
            .uptime-summary { gap:16px; }
            .uptime-num { font-size:1.2rem; }
            .monitor-item { flex-direction:column;align-items:flex-start;gap:12px; }
            .monitor-right { text-align:start; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">MONITORLY</div>
            <div class="subtitle">{{ $user->name }} — Status Page</div>
        </div>

        <!-- Overall Status -->
        <div class="overall {{ $someDown ? 'some-down' : 'all-up' }}">
            <div class="overall-icon">{{ $someDown ? '🔴' : '🟢' }}</div>
            <div class="overall-text">
                {{ $someDown ? 'Some Services Down' : 'All Systems Operational' }}
            </div>
            <div class="overall-sub">
                {{ $monitors->count() }} {{ $monitors->count() === 1 ? 'service' : 'services' }} monitored
            </div>
            <div class="uptime-summary">
                <div class="uptime-item">
                    <div class="uptime-num" style="color:var(--green);">{{ $monitors->where('status', 'up')->count() }}</div>
                    <div class="uptime-label">Online</div>
                </div>
                <div class="uptime-item">
                    <div class="uptime-num" style="color:var(--red);">{{ $monitors->where('status', 'down')->count() }}</div>
                    <div class="uptime-label">Down</div>
                </div>
                <div class="uptime-item">
                    <div class="uptime-num" style="color:var(--blue);">{{ round($overallUptime, 1) }}%</div>
                    <div class="uptime-label">Uptime</div>
                </div>
            </div>
        </div>

        <!-- Monitors -->
        <div class="monitor-list">
            @foreach($monitors as $monitor)
                <div class="monitor-item">
                    <div class="monitor-left">
                        <div class="status-dot {{ $monitor->status }}"></div>
                        <div>
                            <div class="monitor-name">{{ $monitor->name }}</div>
                            <div class="monitor-url" dir="ltr">{{ $monitor->url }}</div>
                        </div>
                    </div>
                    <div class="monitor-right">
                        <div class="monitor-uptime" style="color:{{ $monitor->uptime_percentage >= 99 ? 'var(--green)' : ($monitor->uptime_percentage >= 95 ? 'var(--yellow)' : 'var(--red)') }};">
                            {{ $monitor->uptime_percentage }}%
                        </div>
                        @if($monitor->response_time)
                            <div class="monitor-response">{{ $monitor->response_time }}ms</div>
                        @endif
                        <div class="monitor-checked">
                            {{ $monitor->last_checked_at ? $monitor->last_checked_at->diffForHumans() : 'Not checked yet' }}
                        </div>
                    </div>
                </div>
                <div class="uptime-bar">
                    <div class="uptime-fill" style="width:{{ $monitor->uptime_percentage }}%;"></div>
                </div>
            @endforeach
        </div>

        @if($monitors->isEmpty())
            <div style="text-align:center;padding:60px;color:var(--text2);">
                <div style="font-size:2rem;margin-bottom:12px;">📡</div>
                <p>No monitors configured yet.</p>
            </div>
        @endif

        <div class="footer">
            <a href="{{ url('/') }}">MONITORLY</a>
            <div class="time">Last updated: {{ now()->format('Y-m-d H:i:s T') }}</div>
        </div>
    </div>

    <script>
        // Auto-refresh the page every 60 seconds
        setTimeout(() => window.location.reload(), 60000);
    </script>
</body>
</html>
