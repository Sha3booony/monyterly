<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\MonitorLog;
use App\Jobs\CheckMonitorJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    public function index()
    {
        $monitors = Auth::user()->monitors()->latest()->get();
        return view('dashboard.monitors.index', compact('monitors'));
    }

    public function create()
    {
        return view('dashboard.monitors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'interval' => 'required|integer|min:1|max:60',
            'notify_email' => 'boolean',
        ]);

        $monitor = Auth::user()->monitors()->create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'interval' => $validated['interval'],
            'notify_email' => $request->boolean('notify_email', true),
            'status' => 'up', // Will be updated by the immediate check below
            'is_active' => true,
        ]);

        // Immediate first check — run synchronously so user sees real status
        try {
            CheckMonitorJob::dispatchSync($monitor);
        } catch (\Exception $e) {
            // If check fails, mark as down
            $monitor->update(['status' => 'down']);
        }
        $monitor->refresh();

        return redirect()->route('monitors.show', $monitor)
            ->with('success', __('messages.monitor_created'));
    }

    public function show(Monitor $monitor)
    {
        $this->authorize($monitor);

        // Auto-check if monitor is overdue (scheduler fallback)
        if ($monitor->is_active && $this->isOverdue($monitor)) {
            try {
                CheckMonitorJob::dispatchSync($monitor);
                $monitor->refresh();
            } catch (\Exception $e) {
                // Silent fail - don't break the page
            }
        }

        $monitor->load('logs', 'issues');

        $logs = $monitor->logs()->latest()->take(50)->get();
        $issues = $monitor->issues()->latest()->take(10)->get();

        // Stats for charts
        $avgResponseTime = $monitor->logs()->where('status', 'up')->avg('response_time');
        $totalChecks = $monitor->logs()->count();
        $failedChecks = $monitor->logs()->where('status', 'down')->count();
        $totalIssues = $monitor->issues()->count();

        // Response time history for chart (last 24 hours)
        $chartData = $monitor->logs()
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at')
            ->get(['response_time', 'status', 'created_at'])
            ->map(fn($log) => [
                'time' => $log->created_at->format('H:i'),
                'value' => $log->response_time ?? 0,
                'status' => $log->status,
            ]);

        return view('dashboard.monitors.show', compact(
            'monitor', 'logs', 'issues',
            'avgResponseTime', 'totalChecks', 'failedChecks', 'totalIssues', 'chartData'
        ));
    }

    public function edit(Monitor $monitor)
    {
        $this->authorize($monitor);
        return view('dashboard.monitors.edit', compact('monitor'));
    }

    public function update(Request $request, Monitor $monitor)
    {
        $this->authorize($monitor);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'interval' => 'required|integer|min:1|max:60',
            'notify_email' => 'boolean',
        ]);

        $monitor->update([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'interval' => $validated['interval'],
            'notify_email' => $request->boolean('notify_email', true),
        ]);

        return redirect()->route('monitors.show', $monitor)
            ->with('success', __('messages.monitor_updated'));
    }

    public function destroy(Monitor $monitor)
    {
        $this->authorize($monitor);
        $monitor->delete();

        return redirect()->route('monitors.index')
            ->with('success', __('messages.monitor_deleted'));
    }

    public function togglePause(Monitor $monitor)
    {
        $this->authorize($monitor);
        
        $wasActive = $monitor->is_active;
        $monitor->update([
            'is_active' => !$wasActive,
            'status' => !$wasActive ? $monitor->status : 'paused', // Keep previous status when resuming
        ]);

        // If resuming, do an immediate check
        if (!$wasActive) {
            try {
                CheckMonitorJob::dispatchSync($monitor);
                $monitor->refresh();
            } catch (\Exception $e) {
                $monitor->update(['status' => 'down']);
            }
        }

        return back()->with('success',
            $monitor->is_active ? __('messages.monitor_resumed') : __('messages.monitor_paused')
        );
    }

    /**
     * Manual check — "Check Now" button
     */
    public function checkNow(Monitor $monitor)
    {
        $this->authorize($monitor);
        CheckMonitorJob::dispatchSync($monitor);
        $monitor->refresh();

        return back()->with('success', __('messages.check_completed'));
    }

    /**
     * Export issues as CSV
     */
    public function exportIssues(Monitor $monitor)
    {
        $this->authorize($monitor);
        $issues = $monitor->issues()->latest()->get();

        $csvContent = "ID,Title,Status,Severity,Started At,Resolved At,Duration,Error\n";
        foreach ($issues as $issue) {
            $csvContent .= "{$issue->id},\"{$issue->title}\",{$issue->status},{$issue->severity},{$issue->started_at},{$issue->resolved_at},{$issue->formatted_duration},\"{$issue->error_message}\"\n";
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$monitor->name}-issues.csv\"",
        ]);
    }

    /**
     * Check if a monitor is overdue for a check (past its interval)
     */
    private function isOverdue(Monitor $monitor): bool
    {
        if (!$monitor->last_checked_at) {
            return true; // Never checked
        }
        
        // Add 1 minute buffer to avoid checking too eagerly
        return $monitor->last_checked_at->addMinutes($monitor->interval + 1)->isPast();
    }

    private function authorize(Monitor $monitor)
    {
        if ($monitor->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
