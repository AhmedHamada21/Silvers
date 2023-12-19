<?php

namespace App\Services\Dashboard\CallCenter;

use App\Models\Captain;
use App\Models\CaptainProfile;

class CaptainService
{
    public function getProfile($captainId)
    {
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
