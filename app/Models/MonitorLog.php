<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'status',
        'response_time',
        'http_status_code',
        'error_message',
    ];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
