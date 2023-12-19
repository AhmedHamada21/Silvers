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

            $userCallCenterId = optional(get_user_data())->id;
            $captainCallCenterId = $check->callcenter_id;

            if ($captainCallCenterId && $captainCallCenterId == $userCallCenterId) {
                return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                    $query->where('uuid', $captainId);
                })->firstOrFail();
            } else {

                return redirect()->route('CallCenterCaptains.index')->withErrors([
                    'error' => 'You are not authorized to view this captain\'s profile.'
                ]);
            }
        } else {
            // If there is no captain profile, register a new captain
            $check->update([
                'callcenter_id' => auth('call-center')->id(),
            ]);

            return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
                $query->where('uuid', $captainId);
            })->firstOrFail();
        }

        // Redirect to the main page if there is a problem
        return redirect()->route('CallCenterCaptains.index')->with('error', 'There is a problem. Please try again later');
    }




    public function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['callcenter_id'] = get_user_data()->id;
        return Captain::create($data);
    }


}
