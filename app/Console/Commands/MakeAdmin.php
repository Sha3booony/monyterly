<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin {email}';
    protected $description = 'Make a user admin by their email address';

    public function handle(): void
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return;
        }

        if ($user->is_admin) {
            $this->warn("{$user->name} is already an admin.");
            return;
        }

        $user->update(['is_admin' => true]);
        $this->info("✅ {$user->name} ({$user->email}) is now an admin!");
    }
}
