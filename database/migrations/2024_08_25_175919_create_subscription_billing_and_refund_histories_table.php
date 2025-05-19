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
        Schema::create('subscription_billing_and_refund_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->foreignId('subscription_id');
            $table->foreignId('package_id')->nullable();
            $table->enum('transaction_type',['pending_bill','refund'])->default('pending_bill');
            $table->double('amount', 24, 3);
            $table->boolean('is_success')->default(0);
            $table->string('reference', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_billing_and_refund_histories');
    }
};
