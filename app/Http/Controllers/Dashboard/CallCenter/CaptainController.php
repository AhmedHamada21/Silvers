<?php

namespace App\Http\Controllers\Dashboard\CallCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Dashboard\CallCenter\CaptainDataTable;
use App\Services\Dashboard\{CallCenter\CaptainService,General\GeneralService};
use App\Models\{CaptainProfile,CarsCaptionStatus,Captain,Image};

class CaptainController extends Controller {
    public function __construct(protected CaptainDataTable $dataTable, protected CaptainService $captainService, protected GeneralService $generalService) {
        $this->dataTable = $dataTable;
        $this->captainService = $captainService;
        $this->generalService = $generalService;
    }
    
    public function index() {
        $data = [
            'title' => 'Captions',
            'countries' => $this->generalService->getCountries(),
        ];
        return $this->dataTable->render('dashboard.call-center.captains.index',  compact('data'));
    }

    public function show($captainId) {
        try {
            $data = [
                'title' => 'Captain Details',
                'captain' => $this->captainService->getProfile($captainId),
            ];
            return view('dashboard.call-center.captains.show', compact('data'));
        } catch (\Exception $e) {
            return redirect()->route('CallCenterCaptains.index')->with('error', 'An error occurred while getting the captain details');
        }
    }

    public function uploadPersonalMedia(Request $request) {
        if ($request->hasFile('personal_avatar'))
            $this->storeImage($request, 'personal_avatar', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('id_photo_front'))
            $this->storeImage($request, 'id_photo_front', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('id_photo_back'))
            $this->storeImage($request, 'id_photo_back', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('criminal_record'))
            $this->storeImage($request, 'criminal_record', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('captain_license_front'))
            $this->storeImage($request, 'captain_license_front', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('captain_license_back'))
            $this->storeImage($request, 'captain_license_back', $request->get('imageable_id'), $request->get('type'));
        return redirect()->back()->with('success', 'Upload Personal Media Succesfully');
    }

    public function uploadCarMedia(Request $request) {
        if ($request->hasFile('car_license_front'))
            $this->storeImage($request, 'car_license_front', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('car_license_back'))
            $this->storeImage($request, 'car_license_back', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('car_front'))
            $this->storeImage($request, 'car_front', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('car_back'))
            $this->storeImage($request, 'car_back', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('car_right'))
            $this->storeImage($request, 'car_right', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('car_left'))
            $this->storeImage($request, 'car_left', $request->get('imageable_id'), $request->get('type'));
        if ($request->hasFile('car_inside'))
            $this->storeImage($request, 'car_inside', $request->get('imageable_id'), $request->get('type'));
        return redirect()->back()->with('success', 'Upload Car Media Succesfully');
    }

    private function storeImage(Request $request, $field, $imageable, $type) {
        $image = new Image();
        $image->photo_type = $field;
        $image->imageable_type = 'App\Models\Captain';
        $imageable = json_decode($imageable);
        if ($request->file($field)->isValid()) {
            $captainProfile = CaptainProfile::whereCaptainId($imageable->id)->select('uuid')->first();
            if ($captainProfile) {
                $nameWithoutSpaces = str_replace(' ', '_', $imageable->name);
                $request->file($field)->storeAs(
                    $nameWithoutSpaces . '_' . $captainProfile->uuid . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR,
                    $field . '.' . $request->file($field)->getClientOriginalExtension(),
                    'upload_image'
                );
                $image->photo_status = 'accept';
                $image->type = $type;
                $image->filename = $field . '.' . $request->file($field)->getClientOriginalExtension();
                $image->imageable_id = $imageable->id;
                $image->created_by_callcenter_id = get_user_data()->id;
                $image->created_at_callcenter = now();
                $image->save();
            }
        }
    }

    public function updatePersonalMediaStatus(Request $request, $id) {
        try {
            $columns = [
                'personal_avatar' => [
                    'ar'=> 'الصوره الشخصية',
                    'en'=> 'personal avatar',
                ],
                'id_photo_front' => [
                    'ar'=> 'صوره الهوية امام',
                    'en'=> 'Nationality ID front',
                ],
                'id_photo_back' => [
                    'ar'=> 'صوره الهوية خلف',
                    'en'=> 'Nationality ID back',
                ],
                'criminal_record' => [
                    'ar'=> 'السجل الجنائى',
                    'en'=> 'Criminal Record',
                ],
                'captain_license_front' => [
                    'ar'=> 'رخصة السائق امام',
                    'en'=> 'captain license front',
                ],
                'captain_license_back' => [
                    'ar'=> 'رخصة السائق خلف',
                    'en'=> 'captain license back',
                ],
            ];


            $messages = [
                'Reject' => [
                    'ar'=> 'مرفوضه',
                    'en'=> 'Reject',
                ],
                'Accept' => [
                    'ar'=> 'مقبول',
                    'en'=> 'Accept',
                ],
            ];
            $image = Image::find($id);
            $captain = Captain::findOrfail($request->imageable_id);
            $accept = array_key_exists('Accept',$messages) ? $messages['Accept']['ar'] : null;
            $reject = array_key_exists('Reject',$messages) ? $messages['Reject']['ar'] : null;
          
            $specificName = array_key_exists($image->photo_type,$columns) ? $columns[$image->photo_type]['ar'] : null;
            if (!$image) 
                return redirect()->back()->with('error', 'Image not found');
            $updateData = [];
            if ($request->has('photo_status')) {
                $updateData['photo_status'] = $request->input('photo_status');
                $updateData['updated_by_callcenter_id'] = get_user_data()->id;
                $updateData['updated_at_callcenter'] = now();
            }
            
            if ($request->has('reject_reson'))
                $updateData['reject_reson'] = $request->input('reject_reson');
            
            $image->update($updateData);
            $body = ($request->input('photo_status') === 'accept') ? 'Good Your ' . $specificName . ' Successfully' : 'Sorry this image ' .$specificName;
            $title = ($request->input('photo_status') === 'accept') ? $accept . ' ' .$specificName :  ' ' .$reject .' ' . $specificName;
            //sendNotificationCaptain($captain->fcm_token,$body, $title, false);
            return redirect()->back()->with('success', 'Image ' . ucfirst(str_replace('_', ' ', $image->photo_type)) . ' updated status successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during the update: ' . $e->getMessage());
        }
    }

    public function updateCarStatus(Request $request, $id) {
        try {
            $captainId = $request->input('captain_id');
            $fieldName = $request->input('field_name');
            $newStatus = $request->input('status');
            $status = CarsCaptionStatus::findOrFail($id);
            if ($newStatus === 'reject') {
                $captain_profile_uuid = $request->input('captain_profile_uuid');
                $captain_name = $request->input('captain_name');
                $rejectReason = $request->input('reject_message');
                $status->status = $newStatus;
                $status->reject_message = $rejectReason;
                $status->save();
                sendNotificationCaptain($status->captain_profile->owner->fcm_token, 'reject', $status->reject_message);
                return redirect()->back()->with('success', 'Captain car media updated status successfully');
            } else {
                $status->status = $newStatus;
                $status->save();
                sendNotificationCaptain($status->captain_profile->owner->fcm_token, $newStatus, $status->status);
                return redirect()->back()->with('success', 'Captain car media updated status successfully');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating Captain car media status');
        }
    }
}