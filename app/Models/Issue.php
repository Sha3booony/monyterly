<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'user_id',
        'title',
        'description',
        'status',
        'severity',
        'started_at',
        'resolved_at',
        'duration_seconds',
        'http_status_code',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) return '—';

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$seconds}s";
        } elseif ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }
        return "{$seconds}s";
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => '#ff3366',
            'acknowledged' => '#ffaa00',
            'resolved' => '#00ff88',
            'closed' => '#666',
            default => '#666',
        };
    }
}
