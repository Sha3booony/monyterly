<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "=== SMTP Configuration ===\n";
echo "Mailer:     " . config('mail.default') . "\n";
echo "Host:       " . config('mail.mailers.smtp.host') . "\n";
echo "Port:       " . config('mail.mailers.smtp.port') . "\n";
echo "Scheme:     " . config('mail.mailers.smtp.scheme') . "\n";
echo "Username:   " . config('mail.mailers.smtp.username') . "\n";
echo "From:       " . config('mail.from.address') . "\n";
echo "From Name:  " . config('mail.from.name') . "\n";
echo "===========================\n\n";

try {
    echo "Sending test email...\n";
    Mail::raw('This is a test email from Monitorly. If you receive this, your SMTP configuration is working correctly!', function ($message) {
        $message->to(config('mail.from.address'))
                ->subject('Monitorly SMTP Test - ' . now()->format('H:i:s'));
    });
    echo "Email sent successfully!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
