<?php
// database/migrations/2024_01_01_000002_create_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            
            // Add menu_item_id (nullable to preserve history if menu item is deleted)
            $table->unsignedBigInteger('menu_item_id')->nullable();
            
            $table->string('item_name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->text('special_instructions')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('menu_item_id');
            
            // Optional: Add foreign key but with null on delete
            $table->foreign('menu_item_id')
                  ->references('id')
                  ->on('menu_items')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};