<?php
// database/seeders/FixOrderStatusesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class FixOrderStatusesSeeder extends Seeder
{
    public function run(): void
    {
        // Use Eloquent to safely update orders
        Order::where('order_status', 'preparing')
            ->whereNull('admin_confirmed_at')
            ->whereHas('payments', function($query) {
                $query->whereIn('payment_method', ['gcash', 'card']);
            })
            ->update([
                'order_status' => 'pending',
                'confirmed_at' => null,
                'prepared_at' => null
            ]);
        
        // Fix confirmed orders without admin confirmation
        Order::where('order_status', 'confirmed')
            ->whereNull('admin_confirmed_at')
            ->update([
                'order_status' => 'pending',
                'confirmed_at' => null
            ]);
        
        // Fix cash orders in preparing without admin confirmation
        Order::where('order_status', 'preparing')
            ->whereNull('admin_confirmed_at')
            ->whereHas('payments', function($query) {
                $query->where('payment_method', 'cash');
            })
            ->update([
                'order_status' => 'pending',
                'confirmed_at' => null,
                'prepared_at' => null
            ]);
        
        $this->command->info('Fixed order statuses for admin confirmation flow!');
    }
}