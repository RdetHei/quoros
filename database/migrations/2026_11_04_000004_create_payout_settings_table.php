<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payout_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('method')->default('bank'); // bank, paypal
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('currency', 8)->default('USD');
            $table->boolean('auto_payout')->default(true);
            $table->decimal('min_payout', 12, 2)->default(50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_settings');
    }
};
