<?php

namespace App\Services\Dashboard\CallCenter;

use App\Models\Captain;
use App\Models\CaptainProfile;

class CaptainService
{
    public function getProfile($captainId)
    {

        $findCaptions = CaptainProfile::where('uuid', $captainId)->first();

        $check = Captain::where('id', $findCaptions->captain_id)->first();

        if ($check) {

            $checkStatus = get_user_data()->type == "manager";
            if ($checkStatus) {
                return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                    $query->where('uuid', $captainId);
                })->firstOrFail();
            }


            $checkUser = ($check->callcenter_id == true) == get_user_data()->id;


            if ($checkUser) {
                return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                    $query->where('uuid', $captainId);
                })->firstOrFail();
            } else {
                return redirect()->route('CallCenterCaptains.index')->with('error', 'Register the captain with another call center');
            }


        }


        $check->update([
            'callcenter_id' => auth('call-center')->id(),
        ]);

        return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
            $query->where('uuid', $captainId);
        })->firstOrFail();
    }

    public function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['callcenter_id'] = get_user_data()->id;
        return Captain::create($data);
    }


}
