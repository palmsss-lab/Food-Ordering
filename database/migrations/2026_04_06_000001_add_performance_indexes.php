<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Composite index for the most common admin query:
        // WHERE order_status = 'pending' AND admin_confirmed_at IS NULL ORDER BY created_at DESC
        // MySQL can use this to satisfy WHERE + avoid filesort
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['order_status', 'admin_confirmed_at', 'created_at'], 'orders_status_confirmed_created_idx');
            // Covers the confirmed-tab query: admin_confirmed_at IS NOT NULL AND order_status IN (...)
            $table->index(['admin_confirmed_at', 'order_status'], 'orders_confirmed_status_idx');
            // Covers client orders query: user_id + ordered_at DESC
            $table->index(['user_id', 'ordered_at'], 'orders_user_ordered_idx');
        });

        // Composite index so latestOfMany() MAX(id) GROUP BY order_id uses index instead of full scan
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['order_id', 'id'], 'payments_order_id_asc_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_confirmed_created_idx');
            $table->dropIndex('orders_confirmed_status_idx');
            $table->dropIndex('orders_user_ordered_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_order_id_asc_idx');
        });
    }
};
