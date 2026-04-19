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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('refund_amount', 10, 2)->nullable()->after('total');
            $table->text('refund_reason')->nullable()->after('refund_amount');
            $table->unsignedBigInteger('refunded_by')->nullable()->after('refund_reason');
            $table->timestamp('refunded_at')->nullable()->after('refunded_by');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'refund_reason', 'refunded_by', 'refunded_at']);
        });
    }
};
