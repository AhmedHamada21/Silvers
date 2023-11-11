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
        $perPage = 15;


        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $pagedData = $dataAllOrders->slice(($page - 1) * $perPage, $perPage)->all();
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator($pagedData, count($dataAllOrders), $perPage, $page);
        $data = AllOrdersResources::collection($paginatedData);
        return $this->successResponse($data, 'Data returned successfully');
    }



    public function lasts()
    {
        $orders = Order::where('user_id', auth('users-api')->id())->latest()->take(2)->get();
        return $this->successResponse(OrdersResources::collection($orders), 'data return successfully');
    }
}
