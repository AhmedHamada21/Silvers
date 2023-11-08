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
                    if ($timeDifferenceInMinutes == 1) {
                        sendNotificationUser($ordersSaveHour->user->fcm_token, 'من فضلك قم بتأكيد الرحله', 'تأكيد الرحله', true);
                        $orders->update([
                            'status' => "accepted"
                        ]);
                    }

                    $this->comment('Orders Send ' . $timeDifferenceInMinutes);
                }





                // Check Time Out

                $dataCheck = $ordersSaveHour->data . $ordersSaveHour->hours_from;
                $dataCheckTimeOut = Carbon::parse($dataCheck)->addMinutes(20)->format('Y-m-d g:i A');
                $dataNowCheckTimeOut =Carbon::now()->format('Y-m-d g:i A');

                if ($dataCheckTimeOut == $dataNowCheckTimeOut){
                    sendNotificationUser($ordersSaveHour->user->fcm_token, 'لقد تم الغاء الرحله لعدم التأكيد', 'الغاء الرحله', true);
                    $ordersSaveHour->delete();
                }






                //Check Orders Sub Dayes

                $dataCheck = $ordersSaveHour->data;

                $dataSub = Carbon::now()->subDay()->format('Y-m-d');
                $checks = $dataCheck == $dataSub;
                if ($checks == true){
                    sendNotificationUser($ordersSaveHour->user->fcm_token, 'لقد تم الغاء الرحله لعدم التأكيد', 'الغاء الرحله', true);
                    $ordersSaveHour->delete();
                }


            }


        } else {
            $this->comment('Orders Not exiting');
        }


    }
}
