<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\AllOrdersResources;
use App\Http\Resources\Orders\OrdersResources;
use App\Models\Order;
use App\Models\OrderDay;
use App\Models\OrderHour;
use App\Models\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseTrait;

//    public function index()
//    {
//        $orders = Order::where('user_id', auth('users-api')->id())
//            ->whereIn('status', ['done', 'cancel'])
//            ->paginate(15);
//
//        $orderHours = OrderHour::where('user_id', auth('users-api')->id())
//            ->whereIn('status', ['done', 'cancel'])->paginate(15);
//
//
//        $orderDay = OrderDay::where('user_id', auth('users-api')->id())
//            ->whereIn('status', ['done', 'cancel'])->paginate(15);
//
//        $dataAllOrders = $orders->concat($orderHours)->concat($orderDay);
//
//        $data = OrdersResources::collection($dataAllOrders);
//        $response = [
//            'data' => $data,
//            'pagination' => [
//                'total' => $data->total(),
//                'per_page' => $data->perPage(),
//                'current_page' => $data->currentPage(),
//                'last_page' => $data->lastPage(),
//                'from' => $data->firstItem(),
//                'to' => $data->lastItem(),
//                'next_page_url' => $data->nextPageUrl(),
//            ],
//        ];
//        return $this->successResponse($response, 'data return successfully');
//    }

    public function index()
    {
        $userId = auth('users-api')->id();

        $orders = Order::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $orderHours = OrderHour::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $orderDay = OrderDay::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $dataAllOrders = $orders->concat($orderHours)->concat($orderDay);

        $data = AllOrdersResources::collection($dataAllOrders);

        $pagination = [
            'total' => $orders->total() + $orderHours->total() + $orderDay->total(),
            'per_page' => $orders->perPage() + $orderHours->perPage() + $orderDay->perPage(),
            'current_page' => $orders->current_page() + $orderHours->current_page() + $orderDay->current_page(),
            'last_page' => $orders->last_page() + $orderHours->last_page() + $orderDay->last_page(),
            'from' => $orders->from() + $orderHours->from() + $orderDay->from(),
            'to' => $orders->to() + $orderHours->to() + $orderDay->to(),
            'next_page_url' => $orders->next_page_url() + $orderHours->next_page_url() + $orderDay->next_page_url(),
        ];

        $response = [
            'data' => $data,
            'pagination' => $pagination,
        ];

        return $this->successResponse($response, 'Data returned successfully');
    }



    public function lasts()
    {
        $orders = Order::where('user_id', auth('users-api')->id())->latest()->take(2)->get();
        return $this->successResponse(OrdersResources::collection($orders), 'data return successfully');
    }
}
