<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('character_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained('novel_characters')->cascadeOnDelete();
            $table->foreignId('related_character_id')->nullable()->constrained('novel_characters')->cascadeOnDelete();
            $table->string('related_name')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->default('unresolved'); // established, unresolved
            $table->timestamps();

            $table->index(['character_id', 'novel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_relationships');
    }
};
