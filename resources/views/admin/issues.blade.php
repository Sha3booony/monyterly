@extends('layouts.admin')

@section('title', 'All Issues — Admin')
@section('page-title', '🎫 All Issues')

@section('content')
<div class="card">
    <div class="table-wrap" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Monitor</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Severity</th>
                    <th>Started</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($issues as $issue)
                    <tr>
                        <td style="font-family:var(--font-mono);color:var(--text-muted);">{{ $issue->id }}</td>
                        <td style="font-weight:600;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $issue->title }}</td>
                        <td style="color:var(--text-secondary);">{{ $issue->monitor->name ?? '—' }}</td>
                        <td>
                            @if($issue->user)
                                <a href="{{ route('admin.user-detail', $issue->user) }}" style="color:var(--blue);text-decoration:none;">{{ $issue->user->name }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="badge badge-{{ $issue->status }}">{{ $issue->status }}</span></td>
                        <td><span class="badge badge-{{ $issue->severity }}">{{ $issue->severity }}</span></td>
                        <td style="font-size:0.85rem;color:var(--text-muted);">{{ $issue->started_at->format('M d H:i') }}</td>
                        <td style="font-family:var(--font-mono);font-size:0.85rem;">{{ $issue->formatted_duration }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">✅ No issues — Everything is healthy!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $issues->links() }}
</div>
@endsection
