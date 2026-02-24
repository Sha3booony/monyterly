@extends('layouts.admin')

@section('title', $user->name . ' — Admin')
@section('page-title', '👤 ' . $user->name)

@section('content')
<!-- User Info Card -->
<div class="card" style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <span style="font-size:2rem;">👤</span>
                <div>
                    <h2 style="font-family:var(--font-display);font-weight:700;font-size:1.2rem;">{{ $user->name }}</h2>
                    <span style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-secondary);">{{ $user->email }}</span>
                </div>
                @if($user->is_admin)
                    <span class="badge badge-admin">⚡ Admin</span>
                @endif
            </div>
            <div style="display:flex;gap:20px;font-size:0.85rem;color:var(--text-muted);">
                <span>📅 Joined: {{ $user->created_at->format('Y-m-d H:i') }}</span>
                <span>📡 Monitors: <strong style="color:var(--text-primary);">{{ $user->monitors_count }}</strong></span>
                <span>🎫 Issues: <strong style="color:var(--text-primary);">{{ $user->issues_count }}</strong></span>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('status.page', $user->id) }}" class="btn btn-secondary btn-sm" target="_blank">🌐 Status Page</a>
            @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.toggle-admin', $user) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background:{{ $user->is_admin ? 'var(--red)' : 'var(--accent)' }};color:#fff;">
                        {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- User's Monitors -->
<div class="card" style="margin-bottom:24px;">
    <div class="section-header">
        <h3 class="section-title">📡 Monitors ({{ $monitors->count() }})</h3>
    </div>
    @if($monitors->isEmpty())
        <div class="empty">This user has no monitors</div>
    @else
        <div class="table-wrap" style="border:none;">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Uptime</th>
                        <th>Response Time</th>
                        <th>Interval</th>
                        <th>Last Check</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monitors as $monitor)
                        <tr>
                            <td style="font-weight:600;">{{ $monitor->name }}</td>
                            <td style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);" dir="ltr">{{ Str::limit($monitor->url, 35) }}</td>
                            <td><span class="badge badge-{{ $monitor->status }}">{{ $monitor->status }}</span></td>
                            <td style="font-family:var(--font-mono);color:{{ $monitor->uptime_percentage >= 99 ? 'var(--green)' : ($monitor->uptime_percentage >= 95 ? 'var(--yellow)' : 'var(--red)') }};">{{ $monitor->uptime_percentage }}%</td>
                            <td style="font-family:var(--font-mono);">{{ $monitor->response_time ? $monitor->response_time . 'ms' : '—' }}</td>
                            <td style="font-family:var(--font-mono);">{{ $monitor->interval }}min</td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">{{ $monitor->last_checked_at?->diffForHumans() ?? 'Never' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="grid-2">
    <!-- User's Issues -->
    <div class="card">
        <div class="section-header">
            <h3 class="section-title">🎫 Recent Issues</h3>
        </div>
        @if($issues->isEmpty())
            <div class="empty">✅ No issues</div>
        @else
            @foreach($issues as $issue)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                    <div>
                        <div style="font-weight:600;font-size:0.85rem;">{{ Str::limit($issue->title, 30) }}</div>
                        <span style="font-size:0.75rem;color:var(--text-muted);">{{ $issue->monitor->name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <span class="badge badge-{{ $issue->status }}" style="font-size:0.65rem;">{{ $issue->status }}</span>
                        <span class="badge badge-{{ $issue->severity }}" style="font-size:0.65rem;">{{ $issue->severity }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Recent Check Logs -->
    <div class="card">
        <div class="section-header">
            <h3 class="section-title">📋 Recent Logs</h3>
        </div>
        @if($recentLogs->isEmpty())
            <div class="empty">No logs yet</div>
        @else
            <div style="max-height:400px;overflow-y:auto;">
                @foreach($recentLogs as $log)
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);">
                        <span style="width:8px;height:8px;border-radius:50%;background:{{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};flex-shrink:0;"></span>
                        <div style="flex:1;min-width:0;">
                            <span style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);">{{ $log->created_at->format('M d H:i:s') }}</span>
                            @if($log->http_status_code)
                                <span style="font-size:0.75rem;padding:1px 6px;background:{{ $log->http_status_code < 400 ? 'rgba(0,255,136,0.1)' : 'rgba(255,51,102,0.1)' }};border-radius:3px;color:{{ $log->http_status_code < 400 ? '#00ff88' : '#ff3366' }};margin-inline-start:6px;">{{ $log->http_status_code }}</span>
                            @endif
                        </div>
                        <span style="font-family:var(--font-mono);font-size:0.8rem;color:{{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};">{{ $log->response_time ? $log->response_time . 'ms' : 'ERR' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
