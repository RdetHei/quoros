<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('novel_characters', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('name');
            $table->string('cast_type')->default('supporting')->after('role'); // main, supporting
            $table->json('tags')->nullable()->after('description');
            $table->string('anchor')->nullable()->after('tags');
            $table->string('current_chapter')->nullable()->after('anchor');
            $table->text('wants')->nullable()->after('current_chapter');
            $table->text('fears')->nullable()->after('wants');
        });
    }

    public function down(): void
    {
        Schema::table('novel_characters', function (Blueprint $table) {
            $table->dropColumn([
                'tagline',
                'cast_type',
                'tags',
                'anchor',
                'current_chapter',
                'wants',
                'fears',
            ]);
        });
    }
};
