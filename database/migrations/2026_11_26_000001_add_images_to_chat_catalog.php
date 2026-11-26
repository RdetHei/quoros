<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Emoji kini boleh berupa gambar hasil upload (image_url) maupun emoji
        // unicode. "shortcode" dipakai sebagai penanda reaksi & sisipan teks,
        // mis. :fire: yang dirender menjadi gambar.
        Schema::table('chat_emojis', function (Blueprint $table) {
            $table->string('emoji', 32)->nullable()->change();
        });

        Schema::table('chat_emojis', function (Blueprint $table) {
            $table->string('shortcode', 64)->nullable()->unique()->after('emoji');
            $table->string('image_url', 512)->nullable()->after('label');
            $table->string('image_public_id', 255)->nullable()->after('image_url');
        });

        Schema::table('chat_sticker_items', function (Blueprint $table) {
            $table->string('image_url', 512)->nullable()->after('label');
            $table->string('image_public_id', 255)->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('chat_emojis', function (Blueprint $table) {
            $table->dropUnique(['shortcode']);
            $table->dropColumn(['shortcode', 'image_url', 'image_public_id']);
        });

        Schema::table('chat_sticker_items', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'image_public_id']);
        });
    }
};
