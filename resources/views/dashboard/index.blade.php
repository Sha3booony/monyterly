@extends('layouts.dashboard')

@section('title', __('messages.dashboard') . ' — Monitorly')
@section('page-title', __('messages.overview'))

@section('topbar-actions')
    <a href="{{ route('monitors.create') }}" class="btn btn-primary btn-sm">➕ {{ __('messages.add_monitor') }}</a>
@endsection

@section('styles')
<style>
    .live-indicator { display:inline-flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--accent);padding:4px 12px;border-radius:20px;background:rgba(0,255,136,0.08);border:1px solid rgba(0,255,136,0.2); }
    .live-dot { width:8px;height:8px;border-radius:50%;background:#00ff88;animation:blink 1.5s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
    .monitor-card { text-decoration:none;color:inherit;display:block;padding:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);transition:all 0.3s; }
    .monitor-card:hover { border-color:rgba(0,255,136,0.3);transform:translateY(-2px);box-shadow:var(--shadow-glow); }
    .last-check-box { margin-top:12px;padding:12px;background:var(--bg-secondary);border-radius:var(--radius-sm);border:1px solid var(--border);font-size:0.85rem; }
    .last-check-row { display:flex;justify-content:space-between;align-items:center;padding:4px 0; }
    .last-check-label { color:var(--text-muted);font-size:0.8rem; }
</style>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-label">{{ __('messages.total_monitors') }}</div>
        <div class="stat-value" id="stat-total">{{ $totalMonitors }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">{{ __('messages.sites_up') }}</div>
        <div class="stat-value" id="stat-up">{{ $upMonitors }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">{{ __('messages.sites_down') }}</div>
        <div class="stat-value" id="stat-down">{{ $downMonitors }}</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-label">{{ __('messages.open_issues') }}</div>
        <div class="stat-value" id="stat-issues">{{ $openIssues }}</div>
    </div>
</div>

<!-- Extra Stats Row -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(160px, 1fr));margin-bottom:32px;">
    <div class="stat-card green" style="padding:16px;">
        <div class="stat-label" style="font-size:0.75rem;">{{ __('messages.overall_uptime') }}</div>
        <div class="stat-value" style="font-size:1.3rem;">{{ $overallUptime ? round($overallUptime, 1) : 100 }}%</div>
    </div>
    <div class="stat-card blue" style="padding:16px;">
        <div class="stat-label" style="font-size:0.75rem;">{{ __('messages.avg_response') }}</div>
        <div class="stat-value" style="font-size:1.3rem;">{{ $avgResponseTime ? round($avgResponseTime) . 'ms' : '—' }}</div>
    </div>
    <div class="stat-card yellow" style="padding:16px;">
        <div class="stat-label" style="font-size:0.75rem;">{{ __('messages.checks_today') }}</div>
        <div class="stat-value" style="font-size:1.3rem;">{{ number_format($totalChecksToday) }}</div>
    </div>
    <div class="stat-card red" style="padding:16px;">
        <div class="stat-label" style="font-size:0.75rem;">{{ __('messages.total_incidents') }}</div>
        <div class="stat-value" style="font-size:1.3rem;">{{ $totalIncidents }}</div>
    </div>
</div>

@if($totalMonitors === 0)
    <div class="card">
        <div class="empty-state">
            <div class="icon">📡</div>
            <p>{{ __('messages.no_monitors') }}</p>
            <a href="{{ route('monitors.create') }}" class="btn btn-primary">➕ {{ __('messages.add_monitor') }}</a>
        </div>
    </div>
@else

<!-- Auto-refresh indicator -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <h3 class="section-title">{{ __('messages.monitors') }}</h3>
    <div class="live-indicator"><span class="live-dot"></span> {{ app()->getLocale() === 'ar' ? 'تحديث تلقائي' : 'Auto-refresh' }}</div>
</div>

<!-- Monitors Grid with Last Check -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(340px, 1fr));gap:16px;margin-bottom:32px;">
    @foreach($monitors as $monitor)
        <a href="{{ route('monitors.show', $monitor) }}" class="monitor-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <div style="font-weight:700;font-size:1rem;">{{ $monitor->name }}</div>
                <span class="badge badge-{{ $monitor->status }}">{{ __('messages.' . $monitor->status) }}</span>
            </div>
            <div style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);margin-bottom:4px;" dir="ltr">{{ $monitor->url }}</div>

            <!-- Last Check Details -->
            <div class="last-check-box">
                <div style="font-weight:600;font-size:0.8rem;color:var(--text-secondary);margin-bottom:8px;">
                    🕐 {{ __('messages.last_checked') }}
                </div>
                @if($monitor->last_checked_at)
                    <div class="last-check-row">
                        <span class="last-check-label">{{ app()->getLocale() === 'ar' ? 'الوقت' : 'Time' }}</span>
                        <span style="font-family:var(--font-mono);font-size:0.85rem;">{{ $monitor->last_checked_at->format('H:i:s') }} ({{ $monitor->last_checked_at->diffForHumans() }})</span>
                    </div>
                    <div class="last-check-row">
                        <span class="last-check-label">{{ app()->getLocale() === 'ar' ? 'النتيجة' : 'Result' }}</span>
                        <span style="color:{{ $monitor->status === 'up' ? '#00ff88' : '#ff3366' }};font-weight:600;">
                            {{ $monitor->status === 'up' ? '✅ ' . __('messages.up') : '❌ ' . __('messages.down') }}
                        </span>
                    </div>
                    @if($monitor->response_time)
                    <div class="last-check-row">
                        <span class="last-check-label">{{ __('messages.response_time') }}</span>
                        <span style="font-family:var(--font-mono);color:{{ $monitor->response_time < 500 ? '#00ff88' : ($monitor->response_time < 1000 ? '#ffaa00' : '#ff3366') }};">{{ $monitor->response_time }}ms</span>
                    </div>
                    @endif
                    <div class="last-check-row">
                        <span class="last-check-label">{{ __('messages.uptime') }}</span>
                        <span style="font-family:var(--font-mono);color:{{ $monitor->uptime_percentage >= 99 ? '#00ff88' : ($monitor->uptime_percentage >= 95 ? '#ffaa00' : '#ff3366') }};">{{ $monitor->uptime_percentage }}%</span>
                    </div>
                @else
                    <div style="color:var(--text-muted);font-size:0.8rem;text-align:center;padding:8px 0;">
                        {{ __('messages.never') }}
                    </div>
                @endif
            </div>
        </a>
    @endforeach
</div>

<!-- Recent Issues -->
<div class="card">
    <div class="section-header">
        <h3 class="section-title">🎫 {{ __('messages.recent_issues') }}</h3>
        <a href="{{ route('issues.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.view_all') }}</a>
    </div>

    @if($recentIssues->isEmpty())
        <div class="empty-state" style="padding:30px;">
            <div class="icon">✅</div>
            <p style="margin:0;">{{ __('messages.no_issues') }}</p>
        </div>
    @else
        <div class="table-wrap" style="border:none;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Title' }}</th>
                        <th>{{ __('messages.monitors') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.started_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentIssues as $issue)
                        <tr style="cursor:pointer;" onclick="window.location='{{ route('issues.show', $issue) }}'">
                            <td style="font-family:var(--font-mono);color:var(--text-muted);">#{{ $issue->id }}</td>
                            <td style="font-weight:600;">{{ Str::limit($issue->title, 35) }}</td>
                            <td style="color:var(--text-secondary);">{{ $issue->monitor->name ?? '—' }}</td>
                            <td><span class="badge badge-{{ $issue->status }}">{{ __('messages.' . $issue->status) }}</span></td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">{{ $issue->started_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endif

<!-- Status Page Link -->
@auth
<div class="card" style="margin-top:24px;text-align:center;">
    <div style="font-size:0.9rem;color:var(--text-secondary);margin-bottom:8px;">
        🔗 {{ app()->getLocale() === 'ar' ? 'صفحة الحالة العامة' : 'Your Public Status Page' }}
    </div>
    <a href="{{ route('status.page', auth()->id()) }}" style="font-family:var(--font-mono);color:var(--accent);font-size:0.85rem;">
        {{ url('/status/' . auth()->id()) }}
    </a>
</div>
@endauth
@endsection

@section('scripts')
<script>
// Auto-refresh every 30 seconds
setInterval(() => {
    fetch('{{ route("dashboard.stats") }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('stat-total').textContent = data.total;
            document.getElementById('stat-up').textContent = data.up;
            document.getElementById('stat-down').textContent = data.down;
            document.getElementById('stat-issues').textContent = data.open_issues;
        })
        .catch(() => {});
}, 30000);
</script>
@endsection
