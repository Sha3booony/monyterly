<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run monitor checks every minute
Schedule::command('monitors:check')->everyMinute();

// Prune monitor logs older than 7 days (runs daily at midnight)
Schedule::call(function () {
    DB::table('monitor_logs')->where('created_at', '<', now()->subWeek())->delete();
})->daily();
