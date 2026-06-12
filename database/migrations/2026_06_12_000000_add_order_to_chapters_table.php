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
        if (!Schema::hasColumn('chapters', 'order')) {
            Schema::table('chapters', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('chapters', 'order')) {
            Schema::table('chapters', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
