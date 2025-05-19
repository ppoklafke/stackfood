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
        Schema::create('cash_backs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('customer_id')->default(json_encode(['all']))->nullable();
            $table->string('cashback_type');
            $table->integer('same_user_limit')->default(1);
            $table->integer('total_used')->default(0);
            $table->double('cashback_amount', 23, 3)->default(0);
            $table->double('min_purchase', 23, 3)->default(0);
            $table->double('max_discount', 23, 3)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_backs');
    }
};
