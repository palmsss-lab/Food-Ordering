<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('claimed_at');
            $table->timestamp('used_at')->nullable();
            $table->unique(['user_id', 'voucher_id']); // one claim per user per voucher
        });

        // Add is_public flag to vouchers
        Schema::table('vouchers', function (Blueprint $table) {
            $table->boolean('is_public')->default(true)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
        Schema::dropIfExists('user_vouchers');
    }
};
