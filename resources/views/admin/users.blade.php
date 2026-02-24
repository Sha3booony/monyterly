@extends('layouts.admin')

@section('title', 'Users — Admin')
@section('page-title', '👥 All Users')

@section('content')
<div class="card">
    <div class="table-wrap" style="border:none;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Monitors</th>
                    <th>Issues</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td style="font-family:var(--font-mono);color:var(--text-muted);">{{ $user->id }}</td>
                        <td>
                            <a href="{{ route('admin.user-detail', $user) }}" style="color:var(--text-primary);text-decoration:none;font-weight:600;">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td style="font-family:var(--font-mono);font-size:0.85rem;color:var(--text-secondary);">{{ $user->email }}</td>
                        <td>
                            @if($user->is_admin)
                                <span class="badge badge-admin">⚡ Admin</span>
                            @else
                                <span class="badge badge-user">User</span>
                            @endif
                        </td>
                        <td style="font-family:var(--font-mono);text-align:center;">{{ $user->monitors_count }}</td>
                        <td style="font-family:var(--font-mono);text-align:center;">{{ $user->issues_count }}</td>
                        <td style="font-size:0.85rem;color:var(--text-muted);">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;">
                                <a href="{{ route('admin.user-detail', $user) }}" class="btn btn-secondary btn-xs">View</a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.toggle-admin', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-xs" style="background:{{ $user->is_admin ? 'rgba(255,51,102,0.15)' : 'rgba(255,107,43,0.15)' }};color:{{ $user->is_admin ? '#ff3366' : '#ff6b2b' }};border:1px solid {{ $user->is_admin ? 'rgba(255,51,102,0.3)' : 'rgba(255,107,43,0.3)' }};">
                                            {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.delete-user', $user) }}" onsubmit="return confirm('⚠️ Delete user {{ $user->name }} and ALL their data? This cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs" style="background:rgba(255,51,102,0.1);color:#ff3366;border:1px solid rgba(255,51,102,0.2);">🗑️</button>
                                    </form>
                                @else
                                    <span style="font-size:0.75rem;color:var(--text-muted);">You</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</div>
@endsection
