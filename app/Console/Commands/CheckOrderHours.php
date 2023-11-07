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
    protected $description = 'Check Orders Saved Hours In Minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ordersSaveHours = SaveRentHour::get();
        if ($ordersSaveHours->count() > 0) {
            foreach ($ordersSaveHours as $ordersSaveHour) {
                $orders = SaveRentHour::findorfail($ordersSaveHour->id);
                if ($ordersSaveHour->status == 'cancel') {
                    $ordersSaveHour->delete();
                    $this->comment('Deleted Orders status cancel');
                }


                if ($ordersSaveHour->data == Carbon::now()->format('Y-m-d')) {

                    $timeDifferenceInMinutes = Carbon::now()->diffInMinutes($ordersSaveHour->hours_from);

                    if ($timeDifferenceInMinutes == 20) {
                        sendNotificationUser($ordersSaveHour->user->fcm_token, 'من فضلك قم بتأكيد الرحله', 'تأكيد الرحله', true);
                        $orders->update([
                            'status' => "accepted"
                        ]);
                    }

                    if ($timeDifferenceInMinutes == 10) {
                        sendNotificationUser($ordersSaveHour->user->fcm_token, 'من فضلك قم بتأكيد الرحله', 'تأكيد الرحله', true);
                        $orders->update([
                            'status' => "accepted"
                        ]);;
                    }
                    if ($timeDifferenceInMinutes == 5) {
                        sendNotificationUser($ordersSaveHour->user->fcm_token, 'من فضلك قم بتأكيد الرحله', 'تأكيد الرحله', true);
                        $orders->update([
                            'status' => "accepted"
                        ]);
                    }

                    $newTime = Carbon::parse($ordersSaveHour->hours_from)->addHour()->format('g:i A');
                    $now = Carbon::now()->format('g:i A');
                    if ($now == $newTime) {
                        sendNotificationUser($ordersSaveHour->user->fcm_token, 'لقد تم الغاء الرحله لعدم التأكيد', 'الغاء الرحله', true);
                        $ordersSaveHour->delete();
                        $this->comment('Orders Deleted Successfully');
                    }

                    $this->comment('Orders Send ' . $timeDifferenceInMinutes);
                }

                $dataCheck = $ordersSaveHour->data . ' ' . $ordersSaveHour->hours_from;

                if (Carbon::now()->addMinutes(20) > $dataCheck) {
                    sendNotificationUser($ordersSaveHour->user->fcm_token, 'لقد تم الغاء الرحله لعدم التأكيد', 'الغاء الرحله', true);
                    $ordersSaveHour->delete();
                }




            }
        } else {
            $this->comment('Orders Not exiting');
        }


    }
}
