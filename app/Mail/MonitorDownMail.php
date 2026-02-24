<?php

namespace App\Mail;

use App\Models\Monitor;
use App\Models\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonitorDownMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Monitor $monitor,
        public Issue $issue
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔴 ALERT: {$this->monitor->name} is DOWN — Monitorly",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.monitor-down',
        );
    }
}
