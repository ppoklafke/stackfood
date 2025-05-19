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
        Schema::table('restaurant_configs', function (Blueprint $table) {
            $table->boolean('extra_packaging_status')->default(0);
            $table->boolean('is_extra_packaging_active')->default(0);
            $table->double('extra_packaging_amount', 23, 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_configs', function (Blueprint $table) {
            $table->dropColumn('extra_packaging_status');
            $table->dropColumn('is_extra_packaging_active');
            $table->dropColumn('extra_packaging_amount');
        });
    }
};
