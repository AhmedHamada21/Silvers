<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('car_types', function (Blueprint $table) {
            $table->string('price_normal');
            $table->string('price_premium');

        });

        DB::table('car_types')->where('id', 1)->update([
            'price_normal' => '50', // قد تحتاج لتحويل القيم إلى نص
            'price_premium' => '75',
        ]);

        DB::table('car_types')->where('id', 2)->update([
            'price_normal' => '80',
            'price_premium' => '95',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_types', function (Blueprint $table) {
            $table->dropColumn('price_normal');
            $table->dropColumn('price_premium');
        });
    }
};
