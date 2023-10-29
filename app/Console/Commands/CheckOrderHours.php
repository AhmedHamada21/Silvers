<?php

namespace App\Console\Commands;

use App\Models\OrderHour;
use Illuminate\Console\Command;

class CheckOrderHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-order-hours';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ordersHours = OrderHour::where('data', date('Y-m-d'))->where('status', 'pending')->get();
        foreach ($ordersHours as $ordersHour) {
            dd($ordersHour);
        }


    }
}
