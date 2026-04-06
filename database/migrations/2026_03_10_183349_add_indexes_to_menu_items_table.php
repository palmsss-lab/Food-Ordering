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
        Schema::table('menu_items', function (Blueprint $table) {
            // Create named indexes for easier reference
            $table->index('name', 'menu_items_name_index');                 // For search
            $table->index('categories_id', 'menu_items_categories_id_index'); // For filtering
            $table->index('created_at', 'menu_items_created_at_index');      // For sorting
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Drop the named indexes
            $table->dropIndex('menu_items_name_index');
            $table->dropIndex('menu_items_categories_id_index');
            $table->dropIndex('menu_items_created_at_index');
        });
    }
};