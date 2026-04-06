<?php
// database/migrations/2026_03_19_000000_add_admin_confirmed_at_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add admin_confirmed_at column
            $table->timestamp('admin_confirmed_at')->nullable()->after('order_status');
        });

        // Fix: Remove the index on payment_method if it exists (it shouldn't, but just in case)
        // This is a safe check since the original migration tries to create it
        Schema::table('orders', function (Blueprint $table) {
            // Drop payment_method index if it exists (from the original migration)
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropIndex(['payment_method']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('admin_confirmed_at');
        });
    }
};