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
        Schema::create('restaurant_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('sub_title')->nullable();
            $table->string('key')->nullable();
            $table->foreignId('restaurant_id');
            $table->enum('mail_status',['active','inactive','disable'])->default('disable');
            $table->enum('sms_status',['active','inactive','disable'])->default('disable');
            $table->enum('push_notification_status',['active','inactive','disable'])->default('disable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_notification_settings');
    }
};
