<?php

namespace App\Services\Dashboard\CallCenter;

use App\Models\Captain;
use App\Models\CaptainProfile;

class CaptainService
{
    public function getProfile($captainId)
    {
        $findCaptions = CaptainProfile::where('uuid', $captainId)->first();

        $check = Captain::where('id', optional($findCaptions)->captain_id)->first();

        if ($check) {
            $checkStatus = optional(get_user_data())->type == "manager";
            if ($checkStatus) {
                return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                    $query->where('uuid', $captainId);
                })->firstOrFail();
            }

            if ($check->callcenter_id) {
                $checkUser = $check->callcenter_id == optional(get_user_data())->id;

                if ($checkUser == true) {
                    return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                        $query->where('uuid', $captainId);
                    })->firstOrFail();
                } else {
                    dd('asdasdsadasdsadads');
                    return redirect()->route('CallCenterCaptains.index')->with('error', 'Register the captain with another call center');
                }

            } else {

                $check->update([
                    'callcenter_id' => auth('call-center')->id(),
                ]);

                return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                    $query->where('uuid', $captainId);
                })->firstOrFail();
            }
        }
        return redirect()->route('CallCenterCaptains.index')->with('error', 'There is a problem. Please try again later');
    }


    public function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['callcenter_id'] = get_user_data()->id;
        return Captain::create($data);
    }


}
