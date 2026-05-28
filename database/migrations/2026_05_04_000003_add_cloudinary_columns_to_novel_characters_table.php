<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('novel_characters', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('image');
            $table->string('image_public_id')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('novel_characters', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'image_public_id']);
        });
    }
};
