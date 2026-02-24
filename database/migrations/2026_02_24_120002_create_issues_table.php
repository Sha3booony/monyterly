<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'acknowledged', 'resolved', 'closed'])->default('open');
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('high');
            $table->timestamp('started_at');
            $table->timestamp('resolved_at')->nullable();
            $table->integer('duration_seconds')->nullable(); // total downtime
            $table->integer('http_status_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
