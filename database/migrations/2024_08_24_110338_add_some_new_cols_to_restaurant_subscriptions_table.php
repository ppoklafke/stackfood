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
        Schema::table('restaurant_subscriptions', function (Blueprint $table) {
            $table->integer('validity')->default(0);
            $table->boolean('is_trial')->default(false);
            $table->dateTime('renewed_at')->nullable();
            $table->boolean('is_canceled')->default(false);
            $table->enum('canceled_by',['none','admin','restaurant'])->default('none');
            $table->integer('total_package_renewed')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_subscriptions', function (Blueprint $table) {
            $table->dropColumn('validity');
            $table->dropColumn('is_trial');
            $table->dropColumn('renewed_at');
            $table->dropColumn('is_canceled');
            $table->dropColumn('canceled_by');
            $table->tinyInteger('total_package_renewed')->change();
        });
    }
};
