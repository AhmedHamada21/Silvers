<?php

namespace Database\Seeders;

use App\Models\CarTypeDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CarTypeDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('car_type_days')->truncate();

        CarTypeDay::create([
            'name' => 'سيدان',
            'status' => true,
            'before_price_normal' => 250,
            'discount_price_normal' => 50,
            'discount_price_premium' => 100,
            'before_price_premium' => 350,
        ]);

        CarTypeDay::create([
            'name' => 'Suv',
            'status' => true,
            'before_price_normal' => 350,
            'discount_price_normal' => 50,
            'discount_price_premium' => 200,
            'before_price_premium' => 450,
        ]);



        Schema::enableForeignKeyConstraints();
    }
}
