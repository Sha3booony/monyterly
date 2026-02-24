@extends('layouts.admin')

@section('title', 'Admin Dashboard — Monitorly')
@section('page-title', '⚡ System Overview')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card orange">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Total Monitors</div>
        <div class="stat-value">{{ $totalMonitors }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Monitors Up</div>
        <div class="stat-value">{{ $monitorsUp }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Monitors Down</div>
        <div class="stat-value">{{ $monitorsDown }}</div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-label">Open Issues</div>
        <div class="stat-value">{{ $openIssues }}</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-label">Checks Today</div>
        <div class="stat-value">{{ number_format($totalChecksToday) }}</div>
    </div>
</div>

<!-- Avg Response Time -->
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));margin-bottom:32px;">
    <div class="stat-card blue" style="padding:16px;">
        <div class="stat-label">Avg Response Time (24h)</div>
        <div class="stat-value" style="font-size:1.4rem;">{{ $avgResponseTime ? round($avgResponseTime) . 'ms' : '—' }}</div>
    </div>
    <div class="stat-card red" style="padding:16px;">
        <div class="stat-label">Total Issues (all time)</div>
        <div class="stat-value" style="font-size:1.4rem;">{{ $totalIssues }}</div>
    </div>
</div>

<!-- Down Monitors Alert -->
@if($downMonitors->count() > 0)
<div class="card" style="margin-bottom:24px;border-color:rgba(255,51,102,0.3);background:rgba(255,51,102,0.04);">
    <div class="section-header">
        <h3 class="section-title" style="color:#ff3366;">🚨 Down Monitors ({{ $downMonitors->count() }})</h3>
    </div>
    <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($downMonitors as $monitor)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--bg-secondary);border-radius:var(--radius-sm);border:1px solid rgba(255,51,102,0.15);">
                <div>
                    <span style="font-weight:600;">{{ $monitor->name }}</span>
                    <span style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);margin-inline-start:10px;" dir="ltr">{{ $monitor->url }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="font-size:0.8rem;color:var(--text-muted);">{{ $monitor->user->name ?? '?' }}</span>
                    <span class="badge badge-down">DOWN</span>
                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $monitor->last_checked_at?->diffForHumans() }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid-2">
    <!-- Recent Users -->
    <div class="card">
        <div class="section-header">
            <h3 class="section-title">👥 Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-xs">View All</a>
        </div>
        @if($recentUsers->isEmpty())
            <div class="empty">No users yet</div>
        @else
            @foreach($recentUsers as $user)
                <a href="{{ route('admin.user-detail', $user) }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                    <div>
                        <span style="font-weight:600;">{{ $user->name }}</span>
                        @if($user->is_admin)
                            <span class="badge badge-admin" style="font-size:0.65rem;">ADMIN</span>
                        @endif
                        <div style="font-size:0.8rem;color:var(--text-muted);">{{ $user->email }}</div>
                    </div>
                    <span style="font-size:0.75rem;color:var(--text-muted);">{{ $user->created_at->diffForHumans() }}</span>
                </a>
            @endforeach
        @endif
    </div>

    <!-- Recent Issues -->
    <div class="card">
        <div class="section-header">
            <h3 class="section-title">🎫 Recent Issues</h3>
            <a href="{{ route('admin.issues') }}" class="btn btn-secondary btn-xs">View All</a>
        </div>
        @if($recentIssues->isEmpty())
            <div class="empty">✅ No issues — All systems healthy!</div>
        @else
            @foreach($recentIssues->take(6) as $issue)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:0.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $issue->title }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">
                            {{ $issue->monitor->name ?? '—' }} · {{ $issue->user->name ?? '?' }}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                        <span class="badge badge-{{ $issue->status }}">{{ $issue->status }}</span>
                        <span class="badge badge-{{ $issue->severity }}" style="font-size:0.65rem;">{{ $issue->severity }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Top Monitors -->
@if($topMonitors->count() > 0)
<div class="card" style="margin-top:24px;">
    <div class="section-header">
        <h3 class="section-title">📊 Top Monitors by Checks</h3>
    </div>
    <div class="table-wrap" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Monitor</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Uptime</th>
                    <th>Total Checks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topMonitors as $i => $monitor)
                    <tr>
                        <td style="font-family:var(--font-mono);color:var(--text-muted);">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $monitor->name }}</div>
                            <div style="font-family:var(--font-mono);font-size:0.75rem;color:var(--text-muted);" dir="ltr">{{ Str::limit($monitor->url, 40) }}</div>
                        </td>
                        <td style="color:var(--text-secondary);">{{ $monitor->user->name ?? '?' }}</td>
                        <td><span class="badge badge-{{ $monitor->status }}">{{ $monitor->status }}</span></td>
                        <td style="font-family:var(--font-mono);color:{{ $monitor->uptime_percentage >= 99 ? 'var(--green)' : ($monitor->uptime_percentage >= 95 ? 'var(--yellow)' : 'var(--red)') }};">{{ $monitor->uptime_percentage }}%</td>
                        <td style="font-family:var(--font-mono);">{{ number_format($monitor->logs_count) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
