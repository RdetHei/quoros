<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novel_view_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->date('viewed_on');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->unique(['novel_id', 'viewed_on']);
            $table->index(['viewed_on', 'novel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novel_view_logs');
    }
};
