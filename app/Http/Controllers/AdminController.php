<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Monitor;
use App\Models\Issue;
use App\Models\MonitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard — Overview of everything
     */
    public function index()
    {
        // System-wide stats
        $totalUsers = User::count();
        $totalMonitors = Monitor::count();
        $totalIssues = Issue::count();
        $openIssues = Issue::where('status', 'open')->count();
        $monitorsUp = Monitor::where('status', 'up')->count();
        $monitorsDown = Monitor::where('status', 'down')->count();
        $totalChecksToday = MonitorLog::whereDate('created_at', today())->count();
        $avgResponseTime = MonitorLog::where('status', 'up')
            ->where('created_at', '>=', now()->subDay())
            ->avg('response_time');

        // Recent registrations
        $recentUsers = User::latest()->take(5)->get();

        // Down monitors across all users
        $downMonitors = Monitor::where('status', 'down')
            ->with('user')
            ->latest('last_checked_at')
            ->get();

        // Recent issues across all users
        $recentIssues = Issue::with(['monitor', 'user'])
            ->latest()
            ->take(10)
            ->get();

        // Top monitors by checks
        $topMonitors = Monitor::withCount('logs')
            ->with('user')
            ->orderByDesc('logs_count')
            ->take(10)
            ->get();

        return view('admin.index', compact(
            'totalUsers', 'totalMonitors', 'totalIssues', 'openIssues',
            'monitorsUp', 'monitorsDown', 'totalChecksToday', 'avgResponseTime',
            'recentUsers', 'downMonitors', 'recentIssues', 'topMonitors'
        ));
    }

    /**
     * All Users
     */
    public function users()
    {
        $users = User::withCount(['monitors', 'issues'])
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * View specific user's data
     */
    public function userDetail(User $user)
    {
        $user->loadCount(['monitors', 'issues']);
        $monitors = $user->monitors()->latest()->get();
        $issues = $user->issues()->with('monitor')->latest()->take(20)->get();
        $recentLogs = MonitorLog::whereIn('monitor_id', $monitors->pluck('id'))
            ->latest()
            ->take(30)
            ->get();

        return view('admin.user-detail', compact('user', 'monitors', 'issues', 'recentLogs'));
    }

    /**
     * Toggle admin status for a user
     */
    public function toggleAdmin(User $user)
    {
        // Prevent removing self from admin
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('success',
            $user->is_admin ? "{$user->name} is now an admin." : "{$user->name} is no longer an admin."
        );
    }

    /**
     * All Monitors across all users
     */
    public function monitors()
    {
        $monitors = Monitor::with('user')
            ->withCount(['logs', 'issues'])
            ->latest()
            ->paginate(20);

        return view('admin.monitors', compact('monitors'));
    }

    /**
     * All Issues across all users
     */
    public function issues()
    {
        $issues = Issue::with(['monitor', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.issues', compact('issues'));
    }

    /**
     * System logs — recent check logs
     */
    public function logs()
    {
        $logs = MonitorLog::with('monitor')
            ->latest()
            ->paginate(50);

        return view('admin.logs', compact('logs'));
    }

    /**
     * Delete a user and all their data
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Delete all user's data (cascading)
        $user->monitors()->each(function ($monitor) {
            $monitor->logs()->delete();
            $monitor->issues()->delete();
            $monitor->delete();
        });
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', "User '{$user->name}' and all their data have been deleted.");
    }
}
