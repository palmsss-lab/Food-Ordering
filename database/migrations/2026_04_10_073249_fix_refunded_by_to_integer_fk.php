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
        if (Schema::hasColumn('orders', 'refunded_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('refunded_by_new')->nullable()->after('refunded_by');
            });

            // Backfill: migrate numeric string values to integer (DB-agnostic)
            DB::table('orders')->whereNotNull('refunded_by')->each(function ($row) {
                if (is_numeric($row->refunded_by)) {
                    DB::table('orders')->where('id', $row->id)
                        ->update(['refunded_by_new' => (int) $row->refunded_by]);
                }
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('refunded_by');
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('refunded_by_new', 'refunded_by');
            });
        }

        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('refunded_by')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Throwable $e) {}

        // transactions.refunded_by: same fix
        if (Schema::hasColumn('transactions', 'refunded_by')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('refunded_by_new')->nullable()->after('refunded_by');
            });

            DB::table('transactions')->whereNotNull('refunded_by')->each(function ($row) {
                if (is_numeric($row->refunded_by)) {
                    DB::table('transactions')->where('id', $row->id)
                        ->update(['refunded_by_new' => (int) $row->refunded_by]);
                }
            });

            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('refunded_by');
            });

            Schema::table('transactions', function (Blueprint $table) {
                $table->renameColumn('refunded_by_new', 'refunded_by');
            });
        }

        try {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('refunded_by')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try { Schema::table('transactions', function (Blueprint $table) { $table->dropForeign(['refunded_by']); }); } catch (\Throwable $e) {}
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('refunded_by_old')->nullable()->after('refunded_by');
        });
        DB::table('transactions')->whereNotNull('refunded_by')->each(function ($row) {
            DB::table('transactions')->where('id', $row->id)->update(['refunded_by_old' => (string) $row->refunded_by]);
        });
        Schema::table('transactions', function (Blueprint $table) { $table->dropColumn('refunded_by'); });
        Schema::table('transactions', function (Blueprint $table) { $table->renameColumn('refunded_by_old', 'refunded_by'); });

        try { Schema::table('orders', function (Blueprint $table) { $table->dropForeign(['refunded_by']); }); } catch (\Throwable $e) {}
        Schema::table('orders', function (Blueprint $table) {
            $table->string('refunded_by_old')->nullable()->after('refunded_by');
        });
        DB::table('orders')->whereNotNull('refunded_by')->each(function ($row) {
            DB::table('orders')->where('id', $row->id)->update(['refunded_by_old' => (string) $row->refunded_by]);
        });
        Schema::table('orders', function (Blueprint $table) { $table->dropColumn('refunded_by'); });
        Schema::table('orders', function (Blueprint $table) { $table->renameColumn('refunded_by_old', 'refunded_by'); });
    }
};
