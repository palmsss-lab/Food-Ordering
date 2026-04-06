<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Option A: Replace separate fields with a single text field
            $table->dropColumn(['serving_size', 'serving_unit']);
            $table->string('serving_size')->nullable()->after('description');
            
            // OR Option B: Keep both but make serving_unit nullable and add new field
            // $table->string('serving_size_text')->nullable()->after('serving_unit');
        });
    }

    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('serving_size')->nullable();
            $table->string('serving_unit')->nullable();
            // $table->dropColumn('serving_size_text');
        });
    }
};