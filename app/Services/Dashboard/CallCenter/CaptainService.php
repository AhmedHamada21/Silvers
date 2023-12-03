<?php
namespace App\Services\Dashboard\CallCenter;
use App\Models\Captain;
class CaptainService {
    public function getProfile($captainId) {
        return Captain::with(['profile'])->whereHas('profile', function ($query) use ($captainId) {
            $query->where('uuid', $captainId);
        })->firstOrFail();
    }
}