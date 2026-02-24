@extends('layouts.admin')

@section('title', 'All Monitors — Admin')
@section('page-title', '📡 All Monitors')

@section('content')
<div class="card">
    <div class="table-wrap" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Uptime</th>
                    <th>Response</th>
                    <th>Interval</th>
                    <th>Checks</th>
                    <th>Issues</th>
                    <th>Last Check</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitors as $monitor)
                    <tr>
                        <td style="font-family:var(--font-mono);color:var(--text-muted);">{{ $monitor->id }}</td>
                        <td style="font-weight:600;">{{ $monitor->name }}</td>
                        <td style="font-family:var(--font-mono);font-size:0.8rem;color:var(--text-muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" dir="ltr">{{ $monitor->url }}</td>
                        <td>
                            <a href="{{ route('admin.user-detail', $monitor->user) }}" style="color:var(--blue);text-decoration:none;">{{ $monitor->user->name ?? '?' }}</a>
                        </td>
                        <td><span class="badge badge-{{ $monitor->status }}">{{ $monitor->status }}</span></td>
                        <td style="font-family:var(--font-mono);color:{{ $monitor->uptime_percentage >= 99 ? 'var(--green)' : ($monitor->uptime_percentage >= 95 ? 'var(--yellow)' : 'var(--red)') }};">{{ $monitor->uptime_percentage }}%</td>
                        <td style="font-family:var(--font-mono);">{{ $monitor->response_time ? $monitor->response_time . 'ms' : '—' }}</td>
                        <td style="font-family:var(--font-mono);text-align:center;">{{ $monitor->interval }}m</td>
                        <td style="font-family:var(--font-mono);text-align:center;">{{ number_format($monitor->logs_count) }}</td>
                        <td style="font-family:var(--font-mono);text-align:center;">{{ $monitor->issues_count }}</td>
                        <td style="font-size:0.85rem;color:var(--text-muted);">{{ $monitor->last_checked_at?->diffForHumans() ?? 'Never' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="empty">No monitors found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $monitors->links() }}
</div>
@endsection
