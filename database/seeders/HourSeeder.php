<?php

namespace Database\Seeders;

use App\Models\Hour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('hours')->truncate();

        Hour::create([
            'number_hours' => '1',
            'offer_price' => '0',
            'discount_hours' => '0',
            'price_hours' => '100',
            'price_premium' => '200',
        ]);

        Hour::create([
            'number_hours' => '2',
            'discount_hours' => '10',
            'offer_price' => '10',
            'price_hours' => '250',
            'price_premium' => '350',
        ]);

        Hour::create([
            'number_hours' => '3',
            'discount_hours' => '15',
            'offer_price' => '15',
            'price_hours' => '350',
            'price_premium' => '550',
        ]);
        Hour::create([
            'number_hours' => '4',
            'discount_hours' => '20',
            'offer_price' => '20',
            'price_hours' => '400',
            'price_premium' => '600',
        ]);


        Schema::enableForeignKeyConstraints();
    }
}
