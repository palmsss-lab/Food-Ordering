<?php
// database/migrations/2026_03_19_000002_fix_order_statuses_for_admin_confirmation.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, fix GCash orders that are in 'preparing' but not confirmed by admin
        // We need to use a subquery instead of whereHas in raw SQL
        DB::table('orders')
            ->where('order_status', 'preparing')
            ->whereNull('admin_confirmed_at')
            ->whereIn('id', function($query) {
                $query->select('order_id')
                      ->from('payments')
                      ->whereIn('payment_method', ['gcash', 'card']);
            })
            ->update([
                'order_status' => 'pending',
                'confirmed_at' => null,
                'prepared_at' => null
            ]);
        
        // Fix confirmed orders without admin_confirmed_at
        DB::table('orders')
            ->where('order_status', 'confirmed')
            ->whereNull('admin_confirmed_at')
            ->update([
                'order_status' => 'pending',
                'confirmed_at' => null
            ]);
        
        // Fix cash orders in preparing without admin confirmation
        DB::table('orders')
            ->where('order_status', 'preparing')
            ->whereNull('admin_confirmed_at')
            ->whereIn('id', function($query) {
                $query->select('order_id')
                      ->from('payments')
                      ->where('payment_method', 'cash');
            })
            ->update([
                'order_status' => 'pending',
                'confirmed_at' => null,
                'prepared_at' => null
            ]);
    }

    public function down(): void
    {
        // Cannot easily reverse this
        // You would need to know which orders were changed
    }
};