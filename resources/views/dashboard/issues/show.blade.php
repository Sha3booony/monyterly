@extends('layouts.dashboard')

@section('title', __('messages.issue_details') . ' #' . $issue->id . ' — Monitorly')
@section('page-title', __('messages.issue_details') . ' #' . $issue->id)

@section('topbar-actions')
    <a href="{{ route('issues.index') }}" class="btn btn-secondary btn-sm">← {{ __('messages.back') }}</a>
@endsection

@section('content')
<div style="max-width:800px;">
    <!-- Issue Header -->
    <div class="card" style="margin-bottom:24px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <h2 style="font-size:1.2rem;font-weight:700;margin-bottom:8px;">{{ $issue->title }}</h2>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <span class="badge badge-{{ $issue->status }}">{{ __('messages.' . $issue->status) }}</span>
                    <span class="badge badge-{{ $issue->severity }}">{{ __('messages.' . $issue->severity) }}</span>
                </div>
            </div>
            <div style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-muted);">
                #{{ $issue->id }}
            </div>
        </div>
    </div>

    <!-- Issue Details -->
    <div class="card" style="margin-bottom:24px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:24px;">
            <div>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:6px;">{{ __('messages.monitors') }}</div>
                <div style="font-weight:600;">
                    @if($issue->monitor)
                        <a href="{{ route('monitors.show', $issue->monitor) }}" style="color:var(--accent);text-decoration:none;">
                            {{ $issue->monitor->name }}
                        </a>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:6px;">{{ __('messages.started_at') }}</div>
                <div>{{ $issue->started_at->format('Y-m-d H:i:s') }}</div>
            </div>
            <div>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:6px;">{{ __('messages.resolved_at') }}</div>
                <div>{{ $issue->resolved_at ? $issue->resolved_at->format('Y-m-d H:i:s') : '—' }}</div>
            </div>
            <div>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:6px;">{{ __('messages.duration') }}</div>
                <div style="font-family:var(--font-mono);">{{ $issue->formatted_duration }}</div>
            </div>
        </div>

        @if($issue->error_message)
            <div style="margin-top:24px;padding:16px;background:var(--bg-secondary);border-radius:var(--radius-sm);border:1px solid rgba(255,51,102,0.2);">
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px;">{{ __('messages.error_message') }}</div>
                <div style="font-family:var(--font-mono);font-size:0.85rem;color:var(--danger);word-break:break-all;">{{ $issue->error_message }}</div>
            </div>
        @endif

        @if($issue->description)
            <div style="margin-top:24px;">
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px;">{{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}</div>
                <div style="color:var(--text-secondary);line-height:1.8;">{{ $issue->description }}</div>
            </div>
        @endif
    </div>

    <!-- Update Status -->
    <div class="card">
        <h3 style="font-family:var(--font-display);font-size:0.95rem;font-weight:600;margin-bottom:16px;letter-spacing:0.5px;">
            {{ __('messages.update_status') }}
        </h3>
        <form method="POST" action="{{ route('issues.update-status', $issue) }}">
            @csrf
            @method('PATCH')
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                @foreach(['open', 'acknowledged', 'resolved', 'closed'] as $status)
                    <button type="submit" name="status" value="{{ $status }}"
                        class="btn {{ $issue->status === $status ? 'btn-primary' : 'btn-secondary' }} btn-sm"
                        {{ $issue->status === $status ? 'disabled' : '' }}>
                        {{ __('messages.' . $status) }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>
</div>
@endsection
