@extends('layouts.dashboard')

@section('title', __('messages.monitors') . ' — Monitorly')
@section('page-title', __('messages.monitors'))

@section('topbar-actions')
    <a href="{{ route('monitors.create') }}" class="btn btn-primary btn-sm">➕ {{ __('messages.add_monitor') }}</a>
@endsection

@section('content')
@if($monitors->isEmpty())
    <div class="card">
        <div class="empty-state">
            <div class="icon">📡</div>
            <p>{{ __('messages.no_monitors') }}</p>
            <a href="{{ route('monitors.create') }}" class="btn btn-primary">➕ {{ __('messages.add_monitor') }}</a>
        </div>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.monitor_name') }}</th>
                    <th>{{ __('messages.monitor_url') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.uptime') }}</th>
                    <th>{{ __('messages.response_time') }}</th>
                    <th>{{ __('messages.monitor_interval') }}</th>
                    <th>{{ __('messages.last_checked') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monitors as $monitor)
                    <tr>
                        <td style="font-weight:600;">{{ $monitor->name }}</td>
                        <td style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-secondary);">{{ Str::limit($monitor->url, 35) }}</td>
                        <td><span class="badge badge-{{ $monitor->status }}">{{ __('messages.' . $monitor->status) }}</span></td>
                        <td>
                            <span style="font-family:var(--font-mono);color:{{ $monitor->uptime_percentage >= 99 ? '#00ff88' : ($monitor->uptime_percentage >= 95 ? '#ffaa00' : '#ff3366') }}">
                                {{ $monitor->uptime_percentage }}%
                            </span>
                        </td>
                        <td style="font-family:var(--font-mono);">{{ $monitor->response_time ? $monitor->response_time . 'ms' : '—' }}</td>
                        <td>{{ $monitor->interval }} {{ app()->getLocale() === 'ar' ? 'د' : 'min' }}</td>
                        <td style="font-size:0.85rem;color:var(--text-muted);">{{ $monitor->last_checked_at ? $monitor->last_checked_at->diffForHumans() : __('messages.never') }}</td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('monitors.show', $monitor) }}" class="btn btn-secondary btn-sm">{{ __('messages.details') }}</a>
                                <form method="POST" action="{{ route('monitors.toggle', $monitor) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        {{ $monitor->is_active ? __('messages.pause') : __('messages.resume') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
