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
        Schema::table('add_ons', function (Blueprint $table) {
            $table->string('stock_type',20)->default('unlimited');
            $table->integer('addon_stock')->default(0);
            $table->integer('sell_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_ons', function (Blueprint $table) {
            $table->dropColumn('stock_type');
            $table->dropColumn('addon_stock');
            $table->dropColumn('sell_count');
        });
    }
};
