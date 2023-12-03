<?php
namespace App\Services\Dashboard\Admins;
use App\Models\Callcenter;

class CallCenterService {
    public function create($data) {
        $data['password'] = bcrypt($data['password']);
        $data['admin_id'] = get_user_data()->id;
        return Callcenter::create($data);
    }

    public function getProfile($callCenterId) {
        $relations = [
            'profile',
            'country',
        ];
        return Callcenter::with($relations)->whereHas('profile', function ($query) use ($callCenterId) {
            $query->where('uuid', $callCenterId);
        })->firstOrFail();
    }

    public function update($callCenterId, $data) {
        $data['admin_id'] = get_user_data()->id;
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->fill($data);
        $callCenter->save();
        return $callCenter;
    }

    public function delete($callCenterId) {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->delete();
        return $callCenter;
    }

    public function updatePassword($callCenterId, $password) {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->password = bcrypt($password);
        $callCenter->save();
        return $callCenter;
    }

    public function updateStatus($callCenterId, $status) {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->status = $status;
        $callCenter->save();
        return $callCenter;
    }

    public function updateType($callCenterId, $type) {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->type = $type;
        $callCenter->save();
        return $callCenter;
    }
}