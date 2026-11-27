<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novel_characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('role')->nullable();
            $table->string('cast_type')->default('supporting'); // main, supporting
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('anchor')->nullable();
            $table->string('current_chapter')->nullable();
            $table->text('wants')->nullable();
            $table->text('fears')->nullable();
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_public_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novel_characters');
    }
};
