<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // الاشتراكات الخاصه بالكابتن
        Schema::create('subscription_captions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->enum('type', ['year', 'month', 'week']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_captions');
    }
};
