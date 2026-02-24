<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('url');
            $table->integer('interval')->default(5); // in minutes
            $table->enum('status', ['up', 'down', 'pending', 'paused'])->default('pending');
            $table->integer('consecutive_failures')->default(0);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('last_down_at')->nullable();
            $table->timestamp('last_up_at')->nullable();
            $table->integer('response_time')->nullable(); // in milliseconds
            $table->integer('uptime_percentage')->default(100);
            $table->boolean('is_active')->default(true);
            $table->boolean('notify_email')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
