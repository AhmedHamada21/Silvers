<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\Drivers\CaptionResources;
use App\Http\Resources\HoursResources;
use App\Http\Resources\TripTypeResources;
use App\Http\Resources\Users\RateCommentUserResources;
use App\Http\Resources\Users\UsersResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllOrdersResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => new UsersResources($this->user),
            'captain_id' => new CaptionResources($this->captain),
            'trip_type_id' => new TripTypeResources($this->trip_type)?? null,
//            'complaints' => ComplaintResponse::collection($this->complaints) ?? null,
            'order_code' => $this->order_code ?? null,
            'total_price' => $this->total_price ?? null,
            'chat_id' => $this->chat_id ?? null,
            'status' => $this->status ?? null,
            'payments' => $this->payments ?? null,
            'lat_user' => $this->lat_user ?? null,
            'long_user' => $this->long_user ?? null,
            'lat_going' => $this->lat_going ?? null,
            'long_going' => $this->long_going ?? null,
            'address_now' => $this->address_now ?? null,
            'address_going' => $this->address_going ?? null,
            'time_trips' => $this->time_trips ?? null,
            'distance' => $this->distance ?? null,
            'lat_caption' => $this->lat_caption ?? null,
            'long_caption' => $this->long_caption ?? null,
            'takingOrder' => new TakingOrderResources($this->takingOrder) ?? null,
//            'Rate' => new RateCommentUserResources($this->rates) ?? null,
//            'canselOrders' => new CanselOrderResources($this->canselOrder) ?? null,
//            'hour_id' => new HoursResources($this->hour) ?? null,
            'data' => $this->data ?? null,
            'hours_from' => $this->hours_from ?? null,
            'start_day' => $this->start_day ?? null,
            'end_day' => $this->end_day ?? null,
            'number_day' => $this->number_day ?? null,
            'start_time' => $this->start_time ?? null,

            'create_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'update_dates' => [
                'updated_at_human' => $this->updated_at->diffForHumans(),
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
