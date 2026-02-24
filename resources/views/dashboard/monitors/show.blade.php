@extends('layouts.dashboard')

@section('title', $monitor->name . ' — Monitorly')
@section('page-title', $monitor->name)

@section('topbar-actions')
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <!-- Check Now -->
        <form method="POST" action="{{ route('monitors.check-now', $monitor) }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">🔄 {{ __('messages.check_now') }}</button>
        </form>
        <a href="{{ route('monitors.edit', $monitor) }}" class="btn btn-secondary btn-sm">✏️ {{ __('messages.edit') }}</a>
        <form method="POST" action="{{ route('monitors.toggle', $monitor) }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">
                {{ $monitor->is_active ? '⏸️ ' . __('messages.pause') : '▶️ ' . __('messages.resume') }}
            </button>
        </form>
        <a href="{{ route('monitors.export-issues', $monitor) }}" class="btn btn-secondary btn-sm">📥 CSV</a>
    </div>
@endsection

@section('styles')
<style>
    .chart-container {
        position: relative;
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        margin: 24px 0;
        overflow: hidden;
    }
    .chart-canvas {
        width: 100%;
        height: 200px;
        position: relative;
    }
    .chart-bars {
        display: flex;
        align-items: flex-end;
        gap: 2px;
        height: 160px;
        padding-top: 10px;
    }
    .chart-bar {
        flex: 1;
        min-width: 3px;
        border-radius: 2px 2px 0 0;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .chart-bar:hover {
        opacity: 0.8;
        transform: scaleY(1.05);
        transform-origin: bottom;
    }
    .chart-bar.up { background: linear-gradient(to top, #00cc6a, #00ff88); }
    .chart-bar.down { background: linear-gradient(to top, #cc2255, #ff3366); }
    .chart-bar .tooltip {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--bg-card);
        border: 1px solid var(--border);
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        white-space: nowrap;
        z-index: 10;
        color: var(--text-primary);
        font-family: var(--font-mono);
    }
    .chart-bar:hover .tooltip { display: block; }
    .chart-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 0.7rem;
        color: var(--text-muted);
        font-family: var(--font-mono);
    }
    .uptime-bar-container {
        background: rgba(255,51,102,0.15);
        border-radius: 20px;
        height: 8px;
        overflow: hidden;
        margin-top: 8px;
    }
    .uptime-bar-fill {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #00ff88, #00aaff);
        transition: width 0.8s ease;
    }
    .mini-stat {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: var(--bg-secondary);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
    }
    .mini-stat .icon { font-size: 1.3rem; }
    .mini-stat .info { flex: 1; }
    .mini-stat .label { font-size: 0.8rem; color: var(--text-muted); }
    .mini-stat .val { font-family: var(--font-mono); font-weight: 700; font-size: 1.1rem; }
    .pulse-live { animation: pulse-live 2s infinite; }
    @keyframes pulse-live {
        0%,100% { box-shadow: 0 0 0 0 rgba(0,255,136,0.4); }
        50% { box-shadow: 0 0 0 8px rgba(0,255,136,0); }
    }
</style>
@endsection

@section('content')
<!-- Status Banner -->
<div class="card" style="margin-bottom:24px;border-color:{{ $monitor->status === 'up' ? 'rgba(0,255,136,0.3)' : ($monitor->status === 'down' ? 'rgba(255,51,102,0.3)' : 'rgba(255,170,0,0.3)') }};">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:16px;height:16px;border-radius:50%;background:{{ $monitor->status === 'up' ? '#00ff88' : ($monitor->status === 'down' ? '#ff3366' : '#ffaa00') }};{{ $monitor->status === 'up' ? 'box-shadow:0 0 12px #00ff88;' : ($monitor->status === 'down' ? 'box-shadow:0 0 12px #ff3366;' : '') }}" class="{{ $monitor->status === 'up' ? 'pulse-live' : '' }}"></div>
            <div>
                <span class="badge badge-{{ $monitor->status }}" style="font-size:0.9rem;padding:6px 16px;">{{ __('messages.' . $monitor->status) }}</span>
                <div style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-muted);margin-top:6px;" dir="ltr">{{ $monitor->url }}</div>
            </div>
        </div>
        <div style="text-align:end;">
            <div style="font-size:0.8rem;color:var(--text-muted);">{{ __('messages.last_checked') }}</div>
            <div style="font-family:var(--font-mono);font-size:0.9rem;">{{ $monitor->last_checked_at ? $monitor->last_checked_at->diffForHumans() : __('messages.never') }}</div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));">
    <div class="stat-card green">
        <div class="stat-label">{{ __('messages.uptime') }}</div>
        <div class="stat-value" style="font-size:1.6rem;">{{ $monitor->uptime_percentage }}%</div>
        <div class="uptime-bar-container">
            <div class="uptime-bar-fill" style="width:{{ $monitor->uptime_percentage }}%;"></div>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">{{ __('messages.avg_response') }}</div>
        <div class="stat-value" style="font-size:1.6rem;">{{ $avgResponseTime ? round($avgResponseTime) . 'ms' : '—' }}</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-label">{{ __('messages.total_checks') }}</div>
        <div class="stat-value" style="font-size:1.6rem;">{{ number_format($totalChecks) }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">{{ __('messages.failed_checks') }}</div>
        <div class="stat-value" style="font-size:1.6rem;">{{ $failedChecks }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">{{ __('messages.total_incidents') }}</div>
        <div class="stat-value" style="font-size:1.6rem;">{{ $totalIssues }}</div>
    </div>
</div>

<!-- Response Time Chart -->
@if($chartData->count() > 0)
<div class="card" style="margin-bottom:24px;">
    <div class="section-header">
        <h3 class="section-title">📈 {{ __('messages.response_chart') }}</h3>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ app()->getLocale() === 'ar' ? 'آخر 24 ساعة' : 'Last 24 hours' }}</span>
    </div>
    <div class="chart-container">
        <div class="chart-canvas">
            @php
                $maxResponse = $chartData->max('value') ?: 1;
            @endphp
            <div class="chart-bars">
                @foreach($chartData as $point)
                    <div class="chart-bar {{ $point['status'] }}"
                         style="height:{{ max(4, ($point['value'] / $maxResponse) * 100) }}%;">
                        <div class="tooltip">{{ $point['time'] }} — {{ $point['value'] }}ms</div>
                    </div>
                @endforeach
            </div>
            <div class="chart-labels">
                @if($chartData->count() > 0)
                    <span>{{ $chartData->first()['time'] }}</span>
                    <span>{{ $chartData->last()['time'] }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Monitor Details -->
<div class="card" style="margin-bottom:24px;">
    <div class="section-header" style="margin-bottom:20px;">
        <h3 class="section-title">📋 {{ __('messages.details') }}</h3>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;">
        <div class="mini-stat">
            <span class="icon">🌐</span>
            <div class="info">
                <div class="label">URL</div>
                <div class="val" style="font-size:0.85rem;word-break:break-all;" dir="ltr">{{ $monitor->url }}</div>
            </div>
        </div>
        <div class="mini-stat">
            <span class="icon">⏱️</span>
            <div class="info">
                <div class="label">{{ __('messages.monitor_interval') }}</div>
                <div class="val">{{ $monitor->interval }} {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'min' }}</div>
            </div>
        </div>
        <div class="mini-stat">
            <span class="icon">📧</span>
            <div class="info">
                <div class="label">{{ __('messages.notify_email') }}</div>
                <div class="val">{{ $monitor->notify_email ? '✅' : '❌' }}</div>
            </div>
        </div>
        <div class="mini-stat">
            <span class="icon">📅</span>
            <div class="info">
                <div class="label">{{ __('messages.created_at') }}</div>
                <div class="val" style="font-size:0.85rem;">{{ $monitor->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>
        <div class="mini-stat">
            <span class="icon">🔗</span>
            <div class="info">
                <div class="label">{{ app()->getLocale() === 'ar' ? 'صفحة الحالة' : 'Status Page' }}</div>
                <div class="val" style="font-size:0.75rem;word-break:break-all;">
                    <a href="{{ route('status.page', auth()->id()) }}" style="color:var(--accent);text-decoration:none;">
                        {{ url('/status/' . auth()->id()) }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Check History -->
    <div class="card">
        <div class="section-header">
            <h3 class="section-title">🕐 {{ __('messages.check_history') }}</h3>
        </div>
        @if($logs->isEmpty())
            <div class="empty-state" style="padding:20px;"><p>{{ __('messages.no_data') }}</p></div>
        @else
            <div style="max-height:450px;overflow-y:auto;">
                @foreach($logs as $log)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};box-shadow:0 0 6px {{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};flex-shrink:0;"></span>
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);">{{ $log->created_at->format('M d H:i:s') }}</span>
                                @if($log->http_status_code)
                                    <span style="font-size:0.8rem;padding:2px 8px;background:{{ $log->http_status_code < 400 ? 'rgba(0,255,136,0.1)' : 'rgba(255,51,102,0.1)' }};border-radius:4px;color:{{ $log->http_status_code < 400 ? '#00ff88' : '#ff3366' }};">
                                        {{ $log->http_status_code }}
                                    </span>
                                @endif
                            </div>
                            @if($log->error_message)
                                <div style="font-size:0.75rem;color:var(--danger);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $log->error_message }}</div>
                            @endif
                        </div>
                        <span style="font-family:var(--font-mono);font-size:0.85rem;color:{{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};flex-shrink:0;">
                            {{ $log->response_time ? $log->response_time . 'ms' : 'ERR' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Issues -->
    <div class="card">
        <div class="section-header">
            <h3 class="section-title">🎫 {{ __('messages.issues') }}</h3>
        </div>
        @if($issues->isEmpty())
            <div class="empty-state" style="padding:20px;">
                <div class="icon">✅</div>
                <p>{{ __('messages.no_issues') }}</p>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($issues as $issue)
                    <a href="{{ route('issues.show', $issue) }}" style="text-decoration:none;color:inherit;display:block;padding:14px;background:var(--bg-secondary);border-radius:var(--radius-sm);border:1px solid var(--border);transition:all 0.2s;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <span style="font-weight:600;font-size:0.9rem;">{{ $issue->title }}</span>
                            <span class="badge badge-{{ $issue->status }}">{{ __('messages.' . $issue->status) }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;font-size:0.8rem;color:var(--text-muted);">
                            <span>📅 {{ $issue->started_at->format('M d, H:i') }}</span>
                            <span>⏱️ {{ $issue->formatted_duration }}</span>
                            <span class="badge badge-{{ $issue->severity }}" style="font-size:0.7rem;">{{ __('messages.' . $issue->severity) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
