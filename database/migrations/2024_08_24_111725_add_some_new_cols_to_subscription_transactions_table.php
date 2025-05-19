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
        Schema::table('subscription_transactions', function (Blueprint $table) {
            $table->foreignId('restaurant_subscription_id')->nullable();
            $table->double('previous_due', 24, 3)->default(0);
            $table->boolean('is_trial')->default(false);
            $table->enum('plan_type',['renew','new_plan','first_purchased','free_trial','old_subscription'])->default('old_subscription');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_transactions', function (Blueprint $table) {
            $table->dropColumn('restaurant_subscription_id');
            $table->dropColumn('previous_due');
            $table->dropColumn('is_trial');
            $table->dropColumn('plan_type');
        });
    }
};
