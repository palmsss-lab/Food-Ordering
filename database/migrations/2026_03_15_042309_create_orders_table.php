<?php
// database/migrations/2026_03_15_042309_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            
            // Add user_id to connect to users table
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('order_type', ['pickup'])->default('pickup');

            $table->enum('payment_status', [
                'pending', 
                'cash on pickup', 
                'paid', 
                'failed', 
                'refunded',
                'partial_refund'
            ])->default('pending');
            
            // Refund related fields
            $table->text('refund_reason')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->string('refunded_by')->nullable();
            
            // Rejection reason (for cancelled/rejected orders)
            $table->text('rejection_reason')->nullable();
            
            // Order status
            $table->enum('order_status', [
                'pending', 
                'confirmed', 
                'preparing', 
                'ready', 
                'completed', 
                'cancelled', 
                'rejected',
                'refunded'
            ])->default('pending');
            
            // Financial details
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Additional notes
            $table->text('notes')->nullable();
            
            // Timestamps for each status change - FIXED: Use nullable for ordered_at
            $table->timestamp('ordered_at')->nullable(); // Changed from NOT NULL to NULLABLE
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('order_number');
            $table->index('ordered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};