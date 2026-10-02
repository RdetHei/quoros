<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('last_active_at');
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('active_seconds')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'novel_id']);
            $table->index(['user_id', 'last_active_at']);
            $table->index(['novel_id', 'last_active_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_sessions');
    }
};
