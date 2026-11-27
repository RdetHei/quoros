<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_emojis', function (Blueprint $table) {
            $table->id();
            // Emoji unicode opsional: entri dapat berupa gambar hasil upload.
            $table->string('emoji', 32)->nullable();
            $table->string('shortcode', 64)->nullable()->unique();
            $table->string('label', 64)->nullable();
            $table->string('image_url', 512)->nullable();
            $table->string('image_public_id', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('chat_sticker_packs', function (Blueprint $table) {
            $table->id();
            $table->string('key', 64)->unique();
            $table->string('label', 64);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('chat_sticker_items', function (Blueprint $table) {
            $table->id();
            // TiDB tidak menegakkan FOREIGN KEY; cascade diulang di level aplikasi.
            $table->unsignedBigInteger('chat_sticker_pack_id');
            $table->string('key', 64)->unique();
            $table->string('emoji', 32);
            $table->string('label', 64);
            $table->string('image_url', 512)->nullable();
            $table->string('image_public_id', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['chat_sticker_pack_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sticker_items');
        Schema::dropIfExists('chat_sticker_packs');
        Schema::dropIfExists('chat_emojis');
    }
};
