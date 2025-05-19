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
            $table->boolean('dine_in')->default(false);
            $table->integer('schedule_advance_dine_in_booking_duration')->default(0);
            $table->string('schedule_advance_dine_in_booking_duration_time_format',10)->default('min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_configs', function (Blueprint $table) {
            $table->dropColumn('dine_in');
            $table->dropColumn('schedule_advance_dine_in_booking_duration');
            $table->dropColumn('schedule_advance_dine_in_booking_duration_time_format');
        });
    }
};
