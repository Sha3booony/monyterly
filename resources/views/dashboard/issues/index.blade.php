@extends('layouts.dashboard')

@section('title', __('messages.issues') . ' — Monitorly')
@section('page-title', __('messages.issues'))

@section('content')
@if($issues->isEmpty())
    <div class="card">
        <div class="empty-state">
            <div class="icon">✅</div>
            <p>{{ __('messages.no_issues') }}</p>
        </div>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Title' }}</th>
                    <th>{{ __('messages.monitors') }}</th>
                    <th>{{ __('messages.severity') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.started_at') }}</th>
                    <th>{{ __('messages.duration') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                    <tr>
                        <td style="font-family:var(--font-mono);color:var(--text-muted);">#{{ $issue->id }}</td>
                        <td style="font-weight:600;">{{ Str::limit($issue->title, 40) }}</td>
                        <td style="font-size:0.9rem;color:var(--text-secondary);">{{ $issue->monitor->name ?? '—' }}</td>
                        <td><span class="badge badge-{{ $issue->severity }}">{{ __('messages.' . $issue->severity) }}</span></td>
                        <td><span class="badge badge-{{ $issue->status }}">{{ __('messages.' . $issue->status) }}</span></td>
                        <td style="font-size:0.85rem;color:var(--text-muted);">{{ $issue->started_at->format('M d, H:i') }}</td>
                        <td style="font-family:var(--font-mono);font-size:0.85rem;">{{ $issue->formatted_duration }}</td>
                        <td>
                            <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary btn-sm">{{ __('messages.details') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $issues->links('pagination::simple-default') }}
    </div>
@endif
@endsection
