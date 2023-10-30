<?php

namespace App\Console\Commands;

use App\Models\OrderHour;
use App\Models\SaveRentHour;
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
        $ordersSaveHours = SaveRentHour::get();
        $now = Carbon::now()->format('g:i A');

        if ($ordersSaveHours->count() > 0) {
            foreach ($ordersSaveHours as $ordersSaveHour) {
                if ($ordersSaveHour->status == 'cancel') {
                    $ordersSaveHour->delete();
                }
                $this->comment('Deleted Orders status cancel');
                if ($ordersSaveHour->data == Carbon::now()->format('Y-m-d')) {

                    if ($ordersSaveHour->data == Carbon::now()->format('Y-m-d')) {

                        $timeDifferenceInMinutes = Carbon::now()->diffInMinutes($ordersSaveHour->hours_from);


                        if ($timeDifferenceInMinutes == 10) {
                            sendNotificationUser($ordersSaveHour->user->fcm_token,'تأكيد الرحله','من فضلك قم بتأكيد الرحله',true);
                        }
                        if ($timeDifferenceInMinutes == 5) {
                            sendNotificationUser($ordersSaveHour->user->fcm_token,'تأكيد الرحله','من فضلك قم بتأكيد الرحله',true);

                        }
                        if ($timeDifferenceInMinutes == 20) {
                            sendNotificationUser($ordersSaveHour->user->fcm_token,'تأكيد الرحله','من فضلك قم بتأكيد الرحله',true);

                        }

                    }

                    $this->comment('Orders Send .'  .$timeDifferenceInMinutes);


                }

            }
        }


    }
}
