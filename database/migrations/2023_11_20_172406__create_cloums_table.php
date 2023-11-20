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
        Schema::table('order_hours', function (Blueprint $table) {
            $table->enum('type_duration', ['active', 'inactive'])->default('inactive');
            $table->string('time_duration');
            for ($i = 1; $i <= 5; $i++) {
                $table->string('notes'.$i)->nullable();
            }
        });
        Schema::table('order_days', function (Blueprint $table) {
            $table->enum('type_duration', ['active', 'inactive'])->default('inactive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_hours', function (Blueprint $table) {
            $table->dropColumn('type_duration');
            $table->dropColumn('time_duration');
            for ($i = 1; $i <= 5; $i++) {
                $table->dropColumn('notes'.$i);
            }
        });

        Schema::table('order_days', function (Blueprint $table) {
            $table->dropColumn('type_duration');
        });
    }
};
