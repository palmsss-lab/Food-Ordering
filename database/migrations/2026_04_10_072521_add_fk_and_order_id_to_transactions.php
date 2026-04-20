<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add order_id FK to transactions (keeps order_number for display)
        if (!Schema::hasColumn('transactions', 'order_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('order_id')->nullable()->after('order_number');
                $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
                $table->index('order_id');
            });
        }

        // Backfill order_id for existing rows via order_number (SQLite-compatible)
        DB::statement('
            UPDATE transactions
            SET order_id = (
                SELECT id FROM orders
                WHERE orders.order_number = transactions.order_number
                LIMIT 1
            )
            WHERE order_id IS NULL
        ');

        // 2. Add proper FK constraint on transaction_items.order_item_id
        try {
            Schema::table('transaction_items', function (Blueprint $table) {
                $table->foreign('order_item_id')->references('id')->on('order_items')->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // FK may already exist on existing databases
        }
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['order_item_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropIndex(['order_id']);
            $table->dropColumn('order_id');
        });
    }
};
