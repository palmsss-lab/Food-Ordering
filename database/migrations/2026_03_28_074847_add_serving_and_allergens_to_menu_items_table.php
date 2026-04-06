<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_serving_and_allergens_to_menu_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Serving size fields
            $table->string('serving_size')->nullable()->after('description');
            $table->string('serving_unit')->nullable()->after('serving_size'); // e.g., "g", "ml", "piece", "slice"
            
            // Allergens fields
            $table->json('allergens')->nullable()->after('serving_unit'); // Store as JSON array
            $table->boolean('is_vegetarian')->default(false)->after('allergens');
            $table->boolean('is_vegan')->default(false)->after('is_vegetarian');
            $table->boolean('is_gluten_free')->default(false)->after('is_vegan');
            $table->boolean('is_nut_free')->default(false)->after('is_gluten_free');
            $table->text('allergen_notes')->nullable()->after('is_nut_free');
        });
    }

    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn([
                'serving_size',
                'serving_unit',
                'allergens',
                'is_vegetarian',
                'is_vegan',
                'is_gluten_free',
                'is_nut_free',
                'allergen_notes'
            ]);
        });
    }
};