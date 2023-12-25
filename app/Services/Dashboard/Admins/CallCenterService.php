<?php

namespace App\Services\Dashboard\Admins;

use App\Models\{Callcenter,Captain};

class CallCenterService
{
    public function create($data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['admin_id'] = 1;
        return Callcenter::create($data);
    }

    public function getProfile($callCenterId)
    {
        $relations = [
            'profile',
            'country',
        ];
        return Callcenter::with($relations)->whereHas('profile', function ($query) use ($callCenterId) {
            $query->where('uuid', $callCenterId);
        })->firstOrFail();
    }

    public function update($callCenterId, $data)
    {
        $data['admin_id'] = get_user_data()->id;
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->fill($data);
        $callCenter->save();
        return $callCenter;
    }

    public function delete($callCenterId)
    {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->delete();
        return $callCenter;
    }

    public function updatePassword($callCenterId, $password)
    {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->password = bcrypt($password);
        $callCenter->save();
        return $callCenter;
    }

    public function updateStatus($callCenterId, $status)
    {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->status = $status;
        $callCenter->save();
        return $callCenter;
    }

    public function updateType($callCenterId, $type)
    {
        $callCenter = Callcenter::findOrFail($callCenterId);
        $callCenter->type = $type;
        $callCenter->save();
        return $callCenter;
    }

    public function getCaptains($callCenterId) {
        $callCenter = $this->getProfile($callCenterId);
        $captain = Captain::where('callcenter_id', $callCenter->id)->with('images')->get();
        return $captain;
    }

    public static function getImageStatus($captains) {
        $messages = [];
        foreach ($captains as $captain) {
            $personalPhoto = ['personal_avatar', 'id_photo_front', 'id_photo_back', 'criminal_record', 'captain_license_front', 'captain_license_back'];
            $carPhoto = ['car_license_front', 'car_license_back', 'car_front', 'car_back', 'car_right', 'car_left', 'car_inside'];
            $hasImages = $captain->images()->exists();
            $personalImagesCount = $captain->images()->where('type', 'personal')->count();
            $carImagesCount = $captain->images()->where('type', 'car')->count();
            $requiredPersonalPhotosCount = count($personalPhoto);
            $requiredCarPhotosCount = count($carPhoto);
            $missingPersonalPhotoCount = $requiredPersonalPhotosCount - $personalImagesCount;
            $missingCarPhotoCount = $requiredCarPhotosCount - $carImagesCount;

            $backgroundColor = '';
            if (!$hasImages || $missingPersonalPhotoCount > 0 || $missingCarPhotoCount > 0) {
                $backgroundColor = '#ffc107'; // warning for captain not having any media
                $textColorClass = 'text-dark';
            } else {
                $rejectedImagesExist = $captain->images()->where('photo_status', 'rejected')->exists();
                $allImagesActive = $captain->images()->where('photo_status', 'accept')->count() == ($personalImagesCount + $carImagesCount);
                $notActiveImagesExist = $captain->images()->where('photo_status', 'not_active')->exists();

                if ($allImagesActive) {
                    $backgroundColor = '#28a745'; // success
                    $textColorClass = 'text-white';
                } elseif ($rejectedImagesExist) {
                    $backgroundColor = '#8A2BE2'; // if captain media has a rejected photo
                    $textColorClass = 'text-white';
                } elseif ($notActiveImagesExist) {
                    $backgroundColor = 'gray'; // لون رمادي
                    $textColorClass = 'text-white';
                } else {
                    $backgroundColor = '#dc3545'; // لون خطر للصور المرفوضة
                    $textColorClass = 'text-white';
                }
            }

            $personalMessage = "Personal: $personalImagesCount (Missing: $missingPersonalPhotoCount)";
            $carMessage = "Car: $carImagesCount (Missing: $missingCarPhotoCount)";
            $messages[] = '<span style="background-color: ' . $backgroundColor . '" class="' . $textColorClass . '">' . $personalMessage . '<br>' . $carMessage . '</span>';
        }
        return implode('', $messages);
    }

}