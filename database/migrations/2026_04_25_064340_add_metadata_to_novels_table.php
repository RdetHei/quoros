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
        Schema::table('novels', function (Blueprint $table) {
            $table->string('alternative_title')->nullable()->after('title');
            $table->enum('type', ['web_novel', 'light_novel', 'original'])->default('original')->after('status');
            $table->enum('content_rating', ['everyone', 'teen', 'mature'])->default('everyone')->after('type');
            $table->bigInteger('view_count')->default(0)->after('content_rating');
            $table->decimal('rating_avg', 3, 2)->default(0)->after('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('novels', function (Blueprint $table) {
            $table->dropColumn(['alternative_title', 'type', 'content_rating', 'view_count', 'rating_avg']);
        });
    }
};
