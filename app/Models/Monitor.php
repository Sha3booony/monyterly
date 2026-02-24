<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'interval',
        'status',
        'consecutive_failures',
        'last_checked_at',
        'last_down_at',
        'last_up_at',
        'response_time',
        'uptime_percentage',
        'is_active',
        'notify_email',
    ];

    protected function casts(): array
    {
        return [
            'last_checked_at' => 'datetime',
            'last_down_at' => 'datetime',
            'last_up_at' => 'datetime',
            'is_active' => 'boolean',
            'notify_email' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function logs()
    {
        return $this->hasMany(MonitorLog::class);
    }

    public function openIssues()
    {
        return $this->hasMany(Issue::class)->where('status', 'open');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'up' => '#00ff88',
            'down' => '#ff3366',
            'pending' => '#ffaa00',
            'paused' => '#666',
            default => '#666',
        };
    }
}
