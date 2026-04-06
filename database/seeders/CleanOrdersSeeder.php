<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanOrdersSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate tables (deletes all rows and resets auto-increment)
        DB::table('order_items')->truncate();
        DB::table('payments')->truncate();
        DB::table('orders')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('✅ All orders, order items, and payments have been cleared!');
    }
}