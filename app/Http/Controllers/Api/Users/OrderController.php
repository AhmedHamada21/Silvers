<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\AllOrdersResources;
use App\Http\Resources\Orders\OrdersResources;
use App\Models\Order;
use App\Models\OrderDay;
use App\Models\OrderHour;
use App\Models\SaveRentDay;
use App\Models\SaveRentHour;
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

        $orderSavesHours = SaveRentHour::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $orderSaveDay = SaveRentDay::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $orderHours = OrderHour::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $orderDay = OrderDay::where('user_id', $userId)
            ->whereIn('status', ['done', 'cancel'])
            ->paginate(15);

        $dataAllOrders = $orders->concat($orderHours)->concat($orderDay)->concat($orderSavesHours)->concat($orderSaveDay);

        $data = AllOrdersResources::collection($dataAllOrders);

        $pagination = [
            'total' => $orders->total() + $orderHours->total() + $orderDay->total() + $orderSavesHours->total() + $orderSaveDay->total(),
            'per_page' => $orders->perPage() + $orderHours->perPage() + $orderDay->perPage()  + $orderSavesHours->perPage() + $orderSaveDay->perPage(),
            'current_page' => $orders->currentPage() + $orderHours->currentPage() + $orderDay->currentPage() + $orderSavesHours->currentPage() + $orderSaveDay->currentPage(),
            'last_page' => $orders->lastPage() + $orderHours->lastPage() + $orderDay->lastPage() + $orderSavesHours->lastPage() + $orderSaveDay->lastPage(),
            'from' => $orders->firstItem() + $orderHours->firstItem() + $orderDay->firstItem() + $orderSavesHours->firstItem() + $orderSaveDay->firstItem(),
            'to' => $orders->lastItem() + $orderHours->lastItem() + $orderDay->lastItem() + $orderSavesHours->lastItem() + $orderSaveDay->lastItem(),
            'next_page_url' => $orders->nextPageUrl() + $orderHours->nextPageUrl() + $orderDay->nextPageUrl() + $orderSavesHours->nextPageUrl() + $orderSaveDay->nextPageUrl(),
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
