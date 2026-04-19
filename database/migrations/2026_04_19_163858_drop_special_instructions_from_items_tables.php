<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('special_instructions');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('special_instructions');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('special_instructions');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('special_instructions')->nullable();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('special_instructions')->nullable();
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('special_instructions')->nullable();
        });
    }
};
