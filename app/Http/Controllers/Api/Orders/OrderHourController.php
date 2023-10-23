<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrdersHoursResources;
use App\Http\Resources\Orders\OrdersResources;
use App\Models\CaptionActivity;
use App\Models\OrderHour;
use App\Models\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderHourController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required|exists:order_hours,order_code',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        try {

            $order = OrderHour::where('order_code', $request->order_code)->firstOrFail();
            return $this->successResponse(new OrdersHoursResources($order), 'data return successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'captain_id' => 'required|exists:captains,id',
            'trip_type_id' => 'required|exists:trip_types,id',
            'total_price' => 'required|numeric',
            'payments' => 'required|in:cash,masterCard,wallet',
            'lat_user' => 'required',
            'long_user' => 'required',
            'address_now' => 'required',
            'data' => 'required',
            'hours_from' => 'required',
            'hours_to' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        if (OrderHour::where('user_id', $request->user_id)->where('status', 'pending')->exists()) {
            return $this->errorResponse('This client is already on a journey');
        }

        if (OrderHour::where('captain_id', $request->captain_id)->where('status', 'pending')->exists()) {
            return $this->errorResponse('This captain is already on a journey');
        }
        try {

            $latestOrderId = optional(OrderHour::latest()->first())->id;
            $orderCode = 'orderHour_' . $latestOrderId . generateRandomString(5);
            $chatId = 'chatHour_' . generateRandomString(4);

            $data = OrderHour::create([
                'address_now' => $request->address_now,
                'user_id' => $request->user_id,
                'captain_id' => $request->captain_id,
                'trip_type_id' => $request->trip_type_id,
                'order_code' => $orderCode,
                'total_price' => $request->total_price,
                'chat_id' => $chatId,
                'status' => 'pending',
                'payments' => $request->payments,
                'lat_user' => $request->lat_user,
                'long_user' => $request->long_user,
                'data' => $request->long_user,
                'hours_from' => $request->long_user,
                'hours_to' => $request->long_user,

            ]);

            if ($data) {
                sendNotificationCaptain($request->captain_id, 'Trips Created Successfully', 'New Trips Hours', true);
                sendNotificationUser($request->user_id, 'Trips Created Successfully', 'New Trips Hours', true);
                createInFirebase($request->user_id, $request->captain_id, $data->id);


            }
            return $this->successResponse(new OrdersHoursResources($data), 'Data created successfully');

        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }


    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required|exists:order_hours,order_code',
            'status' => 'required|in:done,waiting,pending,cancel,accepted',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        try {
            $findOrder = OrderHour::where('order_code', $request->order_code)->first();

            if (!$findOrder) {
                return $this->errorResponse('Order not found', 404);
            }

            if ($request->status == 'done') {
                $this->completeOrder($findOrder);
            } else {
                $this->updateOrderStatus($findOrder, $request->status);
            }

            return $this->successResponse(new OrdersResources($findOrder), 'Data updated successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }
    }

    private function completeOrder(OrderHour $order)
    {
        CaptionActivity::where('captain_id', $order->captain_id)->update(['type_captain' => 'active']);
        $order->update(['status' => 'done']);
        sendNotificationUser($order->user->fcm_token, 'لقد تم انتهاء الرحله بنجاح', 'رحله سعيده', true);
        sendNotificationCaptain($order->captain->fcm_token, 'لقد تم انتهاء الرحله بنجاح', 'رحله سعيده كابتن', true);
        DeletedInFirebase($order->user_id, $order->captain_id, $order->id);
    }

    private function updateOrderStatus(OrderHour $order, $status)
    {
        $order->update(['status' => $status]);

        sendNotificationUser($order->user->fcm_token, 'تغير حاله الطلب', $status, true);
        sendNotificationCaptain($order->captain->fcm_token, 'تغير حاله الطلب', $status, true);
    }
}
