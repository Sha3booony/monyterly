@extends('layouts.admin')

@section('title', 'System Logs — Admin')
@section('page-title', '📋 System Logs')

@section('content')
<div class="card">
    <div class="section-header" style="margin-bottom:16px;">
        <span style="font-size:0.85rem;color:var(--text-muted);">All HTTP check logs across the system</span>
    </div>

    <div class="table-wrap" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Monitor</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>HTTP</th>
                    <th>Response</th>
                    <th>Error</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);white-space:nowrap;">{{ $log->created_at->format('M d H:i:s') }}</td>
                        <td style="font-weight:600;">{{ $log->monitor->name ?? '—' }}</td>
                        <td style="color:var(--text-secondary);font-size:0.85rem;">{{ $log->monitor->user->name ?? '?' }}</td>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:5px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:{{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};"></span>
                                {{ strtoupper($log->status) }}
                            </span>
                        </td>
                        <td>
                            @if($log->http_status_code)
                                <span style="font-family:var(--font-mono);font-size:0.85rem;padding:2px 8px;border-radius:4px;background:{{ $log->http_status_code < 400 ? 'rgba(0,255,136,0.1)' : 'rgba(255,51,102,0.1)' }};color:{{ $log->http_status_code < 400 ? '#00ff88' : '#ff3366' }};">{{ $log->http_status_code }}</span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-family:var(--font-mono);color:{{ $log->status === 'up' ? '#00ff88' : '#ff3366' }};">{{ $log->response_time ? $log->response_time . 'ms' : 'ERR' }}</td>
                        <td style="font-size:0.8rem;color:var(--red);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $log->error_message ?? '' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">No logs yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $logs->links() }}
</div>
@endsection
