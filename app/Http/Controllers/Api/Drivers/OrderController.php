<?php

namespace App\Http\Controllers\Api\Drivers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Drivers\OrdersAllResources;
use App\Http\Resources\Drivers\OrdersResources;
use App\Models\Order;
use App\Models\OrderDay;
use App\Models\OrderHour;
use App\Models\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $captainId = auth('captain-api')->id();

        $orders = Order::byCaptain($captainId)
            ->whereIn('status', ['done', 'cancel'])
            ->orderBy('id', 'DESC')
            ->paginate(5);

        $data = OrdersResources::collection($orders);
        $pagination = $orders->toArray();
        unset($pagination['data']); // Remove the 'data' key from pagination

        $response = [
            'data' => $data,
            'pagination' => $pagination,
        ];

        return $this->successResponse($response, 'Data returned successfully');
    }


    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_data' => 'required|date_format:Y-m-d',
            'end_data' => 'nullable',
        ]);
        if ($validator->fails()) {

            return $this->errorResponse($validator->errors(), 422);
        }

        if (isset($request->start_data)) {
            $orders = Order::where('captain_id', auth('captain-api')->id())->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $request->start_data)->get();
            $OrderHour = OrderHour::where('captain_id', auth('captain-api')->id())->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $request->start_data)->get();
            $OrderDay = OrderDay::where('captain_id', auth('captain-api')->id())->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $request->start_data)->get();

            $ordersSum = $orders->sum('total_price');
            $OrderHourSum = $OrderHour->sum('total_price');
            $OrderDaySum = $OrderDay->sum('total_price');

            $data = [
                'total' => $ordersSum + $OrderHourSum + $OrderDaySum,
                'orders' => $orders->concat($OrderHour)->concat($OrderDay),
            ];

            return $this->successResponse(OrdersAllResources::collection($data), 'data returned successfully');

        }

        if (isset($request->start_data) && isset($request->end_data)) {
            $orders = Order::where('captain_id', auth('captain-api')->id())->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$request->start_data, $request->end_data])->get();
            $OrderHour = OrderHour::where('captain_id', auth('captain-api')->id())->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$request->start_data, $request->end_data])->get();
            $OrderDay = OrderDay::where('captain_id', auth('captain-api')->id())->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$request->start_data, $request->end_data])->get();

            $data = $orders->concat($OrderHour)->concat($OrderDay);
            return $this->successResponse(OrdersAllResources::collection($data), 'data return successfully');

        }
        return $this->errorResponse('Something went wrong, please try again later');

    }
}
