<?php

namespace App\Http\Controllers\Dashboard\CallCenter;

use App\DataTables\Dashboard\CallCenter\CaptainSearchDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Dashboard\CallCenter\CaptainDataTable;
use App\Services\Dashboard\{CallCenter\CaptainService, General\GeneralService};
use App\Models\{CaptainProfile, CarsCaptionStatus, Captain, Image, Order, OrderDay, OrderHour, SaveRentHour};
use Illuminate\Support\Facades\DB;

class CaptainController extends Controller
{
    public function __construct(protected CaptainDataTable $dataTable, protected CaptainService $captainService, protected GeneralService $generalService)
    {
        $this->dataTable = $dataTable;
        $this->captainService = $captainService;
        $this->generalService = $generalService;
    }

    public function index()
    {
        $data = [
            'title' => 'Captions',
            'countries' => $this->generalService->getCountries(),
        ];
        return $this->dataTable->render('dashboard.call-center.captains.index', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $requestData = $request->all();
            $this->captainService->create($requestData);
            return redirect()->route('CallCenterCaptains.index')->with('success', 'captain created successfully');
        } catch (\Exception $e) {
            return redirect()->route('CallCenterCaptains.index')->with('error', 'An error occurred while creating the captain');
        }
    }

    public function show($captainId)
    {
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

    public function uploadPersonalMedia(Request $request)
    {
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

    public function uploadCarMedia(Request $request)
    {
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

    private function storeImage(Request $request, $field, $imageable, $type)
    {

        $checkImage = Image::where('imageable_type', 'App\Models\Captain')->where('imageable_id', json_decode($imageable)->id)->where('photo_type', $field)->first();
        if (!$checkImage) {
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
        } else {

            $checkImage->photo_type = $field;
            $checkImage->imageable_type = 'App\Models\Captain';
            $checkImageAble = json_decode($imageable);
            if ($request->file($field)->isValid()) {
                $captainProfile = CaptainProfile::whereCaptainId($checkImageAble->id)->select('uuid')->first();
                if ($captainProfile) {
                    $nameWithoutSpaces = str_replace(' ', '_', $checkImageAble->name);
                    $request->file($field)->storeAs(
                        $nameWithoutSpaces . '_' . $captainProfile->uuid . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR,
                        $field . '.' . $request->file($field)->getClientOriginalExtension(),
                        'upload_image'
                    );
                    $checkImage->photo_status = 'accept';
                    $checkImage->type = $type;
                    $checkImage->filename = $field . '.' . $request->file($field)->getClientOriginalExtension();
                    $checkImage->imageable_id = json_decode($imageable)->id;
                    $checkImage->created_by_callcenter_id = get_user_data()->id;
                    $checkImage->created_at_callcenter = now();
                    $checkImage->save();
                }
            }
        }


    }

    public function updatePersonalMediaStatus(Request $request, $id)
    {

        try {
            $columns = [
                'personal_avatar' => [
                    'ar' => 'الصوره الشخصية',
                    'en' => 'personal avatar',
                ],
                'id_photo_front' => [
                    'ar' => 'صوره الهوية امام',
                    'en' => 'Nationality ID front',
                ],
                'id_photo_back' => [
                    'ar' => 'صوره الهوية خلف',
                    'en' => 'Nationality ID back',
                ],
                'criminal_record' => [
                    'ar' => 'السجل الجنائى',
                    'en' => 'Criminal Record',
                ],
                'captain_license_front' => [
                    'ar' => 'رخصة السائق امام',
                    'en' => 'captain license front',
                ],
                'captain_license_back' => [
                    'ar' => 'رخصة السائق خلف',
                    'en' => 'captain license back',
                ],
            ];


            $messages = [
                'Reject' => [
                    'ar' => 'مرفوضه',
                    'en' => 'Reject',
                ],
                'Accept' => [
                    'ar' => 'مقبول',
                    'en' => 'Accept',
                ],
            ];
            $image = Image::find($id);
            $captain = Captain::findOrfail($request->imageable_id);
            $accept = array_key_exists('Accept', $messages) ? $messages['Accept']['ar'] : null;
            $reject = array_key_exists('Reject', $messages) ? $messages['Reject']['ar'] : null;

            $specificName = array_key_exists($image->photo_type, $columns) ? $columns[$image->photo_type]['ar'] : null;
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
            $body = ($request->input('photo_status') === 'accept') ? 'Good Your ' . $specificName . ' Successfully' : 'Sorry this image ' . $specificName;
            $title = ($request->input('photo_status') === 'accept') ? $accept . ' ' . $specificName : ' ' . $reject . ' ' . $specificName;


            if ($request->photo_status == "accept") {
                sendNotificationCaptain($request->imageable_id, 'تم الموافقه على الورق', '');
            } else {
                sendNotificationCaptain($request->imageable_id, 'هناك خظأ ما', $request->input('reject_reson'));
            }


            return redirect()->back()->with('success', 'Image ' . ucfirst(str_replace('_', ' ', $image->photo_type)) . ' updated status successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during the update: ' . $e->getMessage());
        }
    }

    public function updateCarStatus(Request $request, $id)
    {
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

    /*public function updateActivityStatus(Request $request, $id)
    {
        try {
            $captain = Captain::findOrFail($id);
            $captain->captainActivity->status_captain_work = $request->input('status_captain_work');
            $captain->captainActivity->save();
            return back()->with('success', 'captain activity status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating Captain activity status');
        }
    }*/

    public function blockCaptain(Request $request, Captain $captain) {
        try {
            $captain->captainActivity->status_captain_work = 'block';
            $captain->captainActivity->save();
            DB::table('captain_callcenter_blocks')->insert([
                'call_center_id' => get_user_data()->id,
                'captain_id' => $captain->id,
                'block_reason' => $request->input('block_reason'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Captain activity status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating Captain activity status');
        }
    }



    public function sendNotificationAll(Request $request)
    {
        try {
            sendNotificatioAll($request->type, $request->body, $request->title);
            return redirect()->back();

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'An error occurred');

        }
    }


    public function captains_searchNumber(Request $request)
    {

        try {
            $dataIn = CaptainProfile::where('number_personal', 'like', '%' . $request->number . '%')->orderBy('created_at','desc')->paginate(50);
            if ($dataIn){
                return view('dashboard.call-center.captains.search',compact('dataIn'));
            }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'An error occurred');

        }
    }


    public function trips($captainId)
    {
        $id = CaptainProfile::where('uuid',$captainId)->first()->captain_id;
        $orders = Order::where('captain_id', $id)->orderBy('created_at','desc')->paginate(50);
        $orderHours = OrderHour::where('captain_id', $id)->orderBy('created_at','desc')->paginate(50);
        $orderDay = OrderDay::where('captain_id', $id)->orderBy('created_at','desc')->paginate(50);
        $data = $orders->concat($orderHours)->concat($orderDay);
        $types = ['Orders', 'Order Hours', 'Order Days'];
        return view('dashboard.call-center.captains.trip.trip', compact('data', 'types'));
    }

    public function showOrder($orderCode) {
        $order = Order::where('order_code', $orderCode)->first();
        $orderTracking = Order::where('id', $order->id)->get(['user_id', 'captain_id', 'trip_type_id','id', 'lat_user', 'long_user', 'lat_going', 'long_going']);
        $locations = [];
        foreach ($orderTracking as $orderTrack) {
            $locations[] = [
                'id' => $orderTrack->id,
                'lat_user' => $orderTrack->lat_user,
                'long_user' => $orderTrack->long_user,
                'lat_going' => $orderTrack->lat_going,
                'long_going' => $orderTrack->long_going,
                'user_id' => $orderTrack->user->name,
                'captain_id' => $orderTrack->captain->name,
                'trip_type_id' => $orderTrack->trip_type->name,
            ];
        }
        return view('dashboard.call-center.orders.showOrder', ['order' => $order, 'data' => json_encode($locations)]);
    }

    public function showOrderDay($orderCode) {
        $order = OrderDay::where('order_code', $orderCode)->first();
        $orderTracking = OrderDay::where('id', $order->id)->get(['user_id', 'captain_id', 'trip_type_id','id', 'lat_user', 'long_user', 'status_price', 'car_type_day_id', 'type_duration', 'start_day', 'end_day', 'number_day', 'start_time']);
        $locations = [];
        foreach ($orderTracking as $orderTrack) {
            $locations[] = [
                'id' => $orderTrack->id,
                'lat' => $orderTrack->lat_user,
                'lng' => $orderTrack->long_user,
                'user_id' => $orderTrack->user->name,
                'captain_id' => $orderTrack->captain->name,
                'trip_type_id' => $orderTrack->trip_type->name,
            ];
        }
        return view('dashboard.call-center.orders.showOrderDay', ['order' => $order, 'data' => json_encode($locations)]);
    }

    public function showOrderHour($orderCode) {
        $order = OrderHour::where('order_code', $orderCode)->first();
        $orderTracking = OrderHour::where('id', $order->id)->get(['user_id', 'captain_id', 'trip_type_id','id', 'lat_user', 'long_user', 'status_price', 'car_type_id', 'type_duration', 'time_duration']);
        $locations = [];
        foreach ($orderTracking as $orderTrack) {
            $locations[] = [
                'id' => $orderTrack->id,
                'lat' => $orderTrack->lat_user,
                'lng' => $orderTrack->long_user,
                'user_id' => $orderTrack->user->name,
                'captain_id' => $orderTrack->captain->name,
                'trip_type_id' => $orderTrack->trip_type->name,
            ];
        }
        return view('dashboard.call-center.orders.showOrderHour', ['order' => $order, 'data' => json_encode($locations)]);
    }
}