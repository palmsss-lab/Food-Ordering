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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade');
            
            // Cart item data
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Price at time of adding
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('cart_id');
            $table->index('menu_item_id');
            
            // Prevent duplicate items in same cart
            $table->unique(['cart_id', 'menu_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};