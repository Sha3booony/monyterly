<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Models\MonitorLog;
use App\Models\Issue;
use App\Mail\MonitorDownMail;
use App\Mail\MonitorUpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CheckMonitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Monitor $monitor
    ) {}

    public function handle(): void
    {
        if (!$this->monitor->is_active) return;

        $startTime = microtime(true);
        $isUp = false;
        $statusCode = null;
        $errorMessage = null;

        try {
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->get($this->monitor->url);

            $statusCode = $response->status();
            $isUp = $response->successful() || $response->isRedirect();
            if (!$isUp) {
                $errorMessage = "HTTP {$statusCode}";
            }
        } catch (\Exception $e) {
            $isUp = false;
            $errorMessage = $e->getMessage();
        }

        $responseTime = round((microtime(true) - $startTime) * 1000);

        // Log the check
        MonitorLog::create([
            'monitor_id' => $this->monitor->id,
            'status' => $isUp ? 'up' : 'down',
            'response_time' => $responseTime,
            'http_status_code' => $statusCode,
            'error_message' => $errorMessage,
        ]);

        $previousStatus = $this->monitor->status;

        if ($isUp) {
            $this->handleUp($previousStatus, $responseTime);
        } else {
            $this->handleDown($previousStatus, $statusCode, $errorMessage);
        }

        // Update uptime percentage
        $this->updateUptimePercentage();

        $this->monitor->update(['last_checked_at' => now()]);
    }

    private function handleUp(string $previousStatus, int $responseTime): void
    {
        $this->monitor->update([
            'status' => 'up',
            'consecutive_failures' => 0,
            'last_up_at' => now(),
            'response_time' => $responseTime,
        ]);

        // Was down, now up → send recovery email & resolve issue
        if ($previousStatus === 'down') {
            // Resolve open issues
            $openIssue = $this->monitor->issues()
                ->where('status', 'open')
                ->latest()
                ->first();

            if ($openIssue) {
                $openIssue->update([
                    'status' => 'resolved',
                    'resolved_at' => now(),
                    'duration_seconds' => now()->diffInSeconds($openIssue->started_at),
                ]);
            }

            // Send recovery email
            if ($this->monitor->notify_email) {
                Mail::to($this->monitor->user->email)
                    ->send(new MonitorUpMail($this->monitor, $openIssue));
            }
        }
    }

    private function handleDown(string $previousStatus, ?int $statusCode, ?string $errorMessage): void
    {
        $this->monitor->increment('consecutive_failures');

        $this->monitor->update([
            'status' => 'down',
            'last_down_at' => now(),
        ]);

        // Was up (or pending), now down → create issue & send alert
        if ($previousStatus !== 'down') {
            $issue = Issue::create([
                'monitor_id' => $this->monitor->id,
                'user_id' => $this->monitor->user_id,
                'title' => "{$this->monitor->name} is DOWN",
                'description' => "The website {$this->monitor->url} is not responding. Error: {$errorMessage}",
                'status' => 'open',
                'severity' => 'critical',
                'started_at' => now(),
                'http_status_code' => $statusCode,
                'error_message' => $errorMessage,
            ]);

            if ($this->monitor->notify_email) {
                Mail::to($this->monitor->user->email)
                    ->send(new MonitorDownMail($this->monitor, $issue));
            }
        }
    }

    private function updateUptimePercentage(): void
    {
        $totalLogs = $this->monitor->logs()->count();
        if ($totalLogs === 0) return;

        $upLogs = $this->monitor->logs()->where('status', 'up')->count();
        $percentage = round(($upLogs / $totalLogs) * 100, 1);
        $this->monitor->update(['uptime_percentage' => $percentage]);
    }
}
