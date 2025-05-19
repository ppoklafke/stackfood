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
        Schema::create('cash_back_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_back_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('cashback_type');
            $table->double('calculated_amount', 23, 3)->default(0);
            $table->double('cashback_amount', 23, 3)->default(0);
            $table->double('min_purchase', 23, 3)->default(0);
            $table->double('max_discount', 23, 3)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_back_histories');
    }
};
