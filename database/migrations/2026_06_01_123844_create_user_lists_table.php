<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
        });

        Schema::create('user_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_list_id', 'novel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_list_items');
        Schema::dropIfExists('user_lists');
    }
};
