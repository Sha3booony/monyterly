<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\User;
use App\Jobs\CheckMonitorJob;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    /**
     * Public status page showing all monitors for a user
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $monitors = $user->monitors()
            ->where('is_active', true)
            ->get();

        // Auto-check overdue monitors (scheduler fallback)
        $this->checkOverdueMonitors($monitors);

        // Refresh after checks
        $monitors = $user->monitors()
            ->where('is_active', true)
            ->select('id', 'name', 'url', 'status', 'uptime_percentage', 'response_time', 'last_checked_at')
            ->get();

        $allUp = $monitors->every(fn($m) => $m->status === 'up');
        $someDown = $monitors->contains(fn($m) => $m->status === 'down');
        $overallUptime = $monitors->avg('uptime_percentage') ?? 100;

        return view('status-page', compact('monitors', 'user', 'allUp', 'someDown', 'overallUptime'));
    }

    /**
     * JSON API for status page (for webhooks/integrations)
     */
    public function api($userId)
    {
        $user = User::findOrFail($userId);
        $monitors = $user->monitors()
            ->where('is_active', true)
            ->get();

        // Auto-check overdue monitors
        $this->checkOverdueMonitors($monitors);

        // Refresh after checks
        $monitors = $user->monitors()
            ->where('is_active', true)
            ->select('id', 'name', 'url', 'status', 'uptime_percentage', 'response_time', 'last_checked_at')
            ->get();

        return response()->json([
            'status' => $monitors->every(fn($m) => $m->status === 'up') ? 'operational' : 'degraded',
            'uptime' => round($monitors->avg('uptime_percentage') ?? 100, 2),
            'monitors' => $monitors,
            'last_updated' => now()->toISOString(),
        ]);
    }

    /**
     * Check any monitors that are overdue or still pending
     */
    private function checkOverdueMonitors($monitors): void
    {
        foreach ($monitors as $monitor) {
            $shouldCheck = false;

            // Always check if status is 'pending' (legacy/stuck)
            if ($monitor->status === 'pending') {
                $shouldCheck = true;
            }
            // Check if never checked
            elseif (!$monitor->last_checked_at) {
                $shouldCheck = true;
            }
            // Check if overdue (past interval + 1 min buffer)
            elseif ($monitor->last_checked_at->addMinutes($monitor->interval + 1)->isPast()) {
                $shouldCheck = true;
            }

            if ($shouldCheck) {
                try {
                    CheckMonitorJob::dispatchSync($monitor);
                } catch (\Exception $e) {
                    $monitor->update(['status' => 'down']);
                }
            }
        }
    }
}
