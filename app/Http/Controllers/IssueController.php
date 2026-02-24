<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::where('user_id', Auth::id())
            ->with('monitor')
            ->latest()
            ->paginate(15);

        return view('dashboard.issues.index', compact('issues'));
    }

    public function show(Issue $issue)
    {
        if ($issue->user_id !== Auth::id()) abort(403);
        $issue->load('monitor');
        return view('dashboard.issues.show', compact('issue'));
    }

    public function updateStatus(Request $request, Issue $issue)
    {
        if ($issue->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'status' => 'required|in:open,acknowledged,resolved,closed',
        ]);

        $issue->update(['status' => $validated['status']]);

        if ($validated['status'] === 'resolved' && !$issue->resolved_at) {
            $issue->update([
                'resolved_at' => now(),
                'duration_seconds' => now()->diffInSeconds($issue->started_at),
            ]);
        }

        return back()->with('success', __('messages.issue_updated'));
    }
}
