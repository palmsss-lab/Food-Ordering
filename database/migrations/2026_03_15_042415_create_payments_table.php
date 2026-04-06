<?php
// database/migrations/2024_01_01_000003_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->unique(); // Added unique to prevent duplicates
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'gcash', 'card'])->default('cash');
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('reference_number')->nullable()->unique();
            $table->string('transaction_id')->nullable();
            
            // Payment method specific fields
            $table->json('payment_details')->nullable();
            
            // For GCash
            $table->string('gcash_number')->nullable();
            $table->string('gcash_reference')->nullable();
            
            // For Card
            $table->string('card_last_four')->nullable();
            $table->string('card_type')->nullable();
            
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('payment_number');
            $table->index('payment_status');
            $table->index('reference_number');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};