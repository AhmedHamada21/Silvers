<?php

namespace App\Console\Commands;

use App\Models\OrderHour;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
        $hoursNow = Carbon::now()->format('g:i A');
        $ordersHours = OrderHour::with([
            'user',
            'captain',
        ])->where('data', date('Y-m-d'))->where('status', 'pending')->get();

        foreach ($ordersHours as $ordersHour) {

            $orderTime = Carbon::parse($ordersHour->hours_from);
            $timeDifference = $orderTime->diffInMinutes($hoursNow);

            if ($timeDifference <= 10) {
            }

            if ($timeDifference <= 5) {
            }


        }



    }
}
