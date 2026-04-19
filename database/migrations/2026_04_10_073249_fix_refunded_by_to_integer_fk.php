<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // orders.refunded_by: string → nullable unsignedBigInteger FK to users
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('refunded_by_new')->nullable()->after('refunded_by');
        });

        // Migrate existing string values (stored as user IDs) to integer
        DB::statement('UPDATE orders SET refunded_by_new = CAST(refunded_by AS UNSIGNED) WHERE refunded_by IS NOT NULL AND refunded_by REGEXP \'^[0-9]+$\'');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('refunded_by');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('refunded_by_new', 'refunded_by');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('refunded_by')->references('id')->on('users')->nullOnDelete();
        });

        // transactions.refunded_by: same fix
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('refunded_by_new')->nullable()->after('refunded_by');
        });

        DB::statement('UPDATE transactions SET refunded_by_new = CAST(refunded_by AS UNSIGNED) WHERE refunded_by IS NOT NULL AND refunded_by REGEXP \'^[0-9]+$\'');

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('refunded_by');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('refunded_by_new', 'refunded_by');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('refunded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['refunded_by']);
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('refunded_by_old')->nullable()->after('refunded_by');
        });
        DB::statement('UPDATE transactions SET refunded_by_old = CAST(refunded_by AS CHAR) WHERE refunded_by IS NOT NULL');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('refunded_by');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('refunded_by_old', 'refunded_by');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['refunded_by']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('refunded_by_old')->nullable()->after('refunded_by');
        });
        DB::statement('UPDATE orders SET refunded_by_old = CAST(refunded_by AS CHAR) WHERE refunded_by IS NOT NULL');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('refunded_by');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('refunded_by_old', 'refunded_by');
        });
    }
};
