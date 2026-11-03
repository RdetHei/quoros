<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plot_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('novel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('category')->default('general'); // scene_outline, character_arc, world, continuity, general
            $table->longText('content')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_open_question')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'novel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plot_notes');
    }
};
