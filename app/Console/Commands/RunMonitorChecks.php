<?php

namespace App\Console\Commands;

use App\Jobs\CheckMonitorJob;
use App\Models\Monitor;
use Illuminate\Console\Command;

class RunMonitorChecks extends Command
{
    protected $signature = 'monitors:check';
    protected $description = 'Check all active monitors that are due for checking';

    public function handle(): void
    {
        $monitors = Monitor::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhereRaw('TIMESTAMPDIFF(MINUTE, last_checked_at, NOW()) >= `interval`');
            })
            ->get();

        $this->info("Checking {$monitors->count()} monitors...");

        foreach ($monitors as $monitor) {
            CheckMonitorJob::dispatch($monitor);
            $this->line("→ Dispatched check for: {$monitor->name} ({$monitor->url})");
        }

        $this->info('Done!');
    }
}
