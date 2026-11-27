<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('novels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('alternative_title')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['ongoing', 'hiatus', 'complete'])->default('ongoing');
            $table->enum('type', ['web_novel', 'light_novel', 'original'])->default('original');
            $table->enum('content_rating', ['everyone', 'teen', 'mature'])->default('everyone');
            $table->unsignedTinyInteger('creation_step')->default(1);
            $table->string('region')->nullable();
            $table->string('language')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->bigInteger('view_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->string('cover_image')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->string('cover_public_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novels');
    }
};
