<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\User;
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
            ->select('id', 'name', 'url', 'status', 'uptime_percentage', 'response_time', 'last_checked_at')
            ->get();

        return response()->json([
            'status' => $monitors->every(fn($m) => $m->status === 'up') ? 'operational' : 'degraded',
            'uptime' => round($monitors->avg('uptime_percentage') ?? 100, 2),
            'monitors' => $monitors,
            'last_updated' => now()->toISOString(),
        ]);
    }
}
