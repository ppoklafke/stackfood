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
        Schema::table('food', function (Blueprint $table) {
            $table->integer('item_stock')->default(0);
            $table->integer('sell_count')->default(0);
            $table->string('stock_type',20)->default('unlimited');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn('item_stock');
            $table->dropColumn('sell_count');
            $table->dropColumn('stock_type');
        });
    }
};
