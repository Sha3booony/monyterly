<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\Issue;
use App\Models\MonitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $monitors = $user->monitors()->latest()->get();
        $totalMonitors = $monitors->count();
        $upMonitors = $monitors->where('status', 'up')->count();
        $downMonitors = $monitors->where('status', 'down')->count();
        $openIssues = Issue::where('user_id', $user->id)->where('status', 'open')->count();

        // Extra stats
        $avgResponseTime = MonitorLog::whereIn('monitor_id', $monitors->pluck('id'))
            ->where('status', 'up')
            ->where('created_at', '>=', now()->subDay())
            ->avg('response_time');
        $totalChecksToday = MonitorLog::whereIn('monitor_id', $monitors->pluck('id'))
            ->where('created_at', '>=', now()->startOfDay())
            ->count();
        $overallUptime = $monitors->avg('uptime_percentage');
        $totalIncidents = Issue::where('user_id', $user->id)->count();

        $recentIssues = Issue::where('user_id', $user->id)
            ->with('monitor')
            ->latest()
            ->take(5)
            ->get();

        $recentLogs = MonitorLog::whereIn('monitor_id', $monitors->pluck('id'))
            ->latest()
            ->take(20)
            ->get();

        return view('dashboard.index', compact(
            'monitors', 'totalMonitors', 'upMonitors',
            'downMonitors', 'openIssues', 'recentIssues', 'recentLogs',
            'avgResponseTime', 'totalChecksToday', 'overallUptime', 'totalIncidents'
        ));
    }

    /**
     * AJAX endpoint for auto-refresh stats
     */
    public function stats()
    {
        $user = Auth::user();
        $monitors = $user->monitors()->get();

        return response()->json([
            'total' => $monitors->count(),
            'up' => $monitors->where('status', 'up')->count(),
            'down' => $monitors->where('status', 'down')->count(),
            'open_issues' => Issue::where('user_id', $user->id)->where('status', 'open')->count(),
            'monitors' => $monitors->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'status' => $m->status,
                'response_time' => $m->response_time,
                'uptime' => $m->uptime_percentage,
            ]),
        ]);
    }
}
