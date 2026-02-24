<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['up', 'down']);
            $table->integer('response_time')->nullable(); // ms
            $table->integer('http_status_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['monitor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitor_logs');
    }
};
