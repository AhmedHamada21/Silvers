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
        Schema::create('user_save_rends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('save_rent_day_id')->nullable()->constrained('save_rent_days','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->foreignId('save_rent_hour_id')->nullable()->constrained('save_rent_hours','id')->cascadeOnUpdate()->cascadeOnUpdate();
            $table->boolean('notify_status')->comment('User Notification Status Active or Not Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_save_rends');
    }
};
