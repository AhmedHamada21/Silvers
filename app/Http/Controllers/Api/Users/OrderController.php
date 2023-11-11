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
            'total' => $dataAllOrders->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
            'next_page_url' => $data->nextPageUrl(),
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
