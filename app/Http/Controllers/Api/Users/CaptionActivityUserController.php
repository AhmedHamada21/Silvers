<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\CaptionActivityUserResources;
use App\Models\CaptionActivity;
use App\Models\CarsCaption;
use App\Models\CarType;
use App\Models\CategoryCar;
use App\Models\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptionActivityUserController extends Controller
{
    use ApiResponseTrait;

//    public function captionActivity(Request $request)
//    {
//
//        $validator = Validator::make($request->all(), [
//            'latitude' => 'required',
//            'longitude' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return $this->errorResponse($validator->errors()->first(), 422);
//        }
//
//        try {
//            $latitude = $request->input('latitude');
//            $longitude = $request->input('longitude');
//            $radius = 50;
//            $categoryCars = $request->category_car_id; //  // فئات السيارات ( A , B  , C
//            $CarTypes = $request->car_type_id;  // انواع السياارت ( سيدان - suv
//
//            if ($categoryCars) {
//                if ($categoryCars == 1) {
//                    $CarsCaption = CarsCaption::where('category_car_id', 1)->get();
//                    $captains = CaptionActivity::whereIn('captain_id', $CarsCaption->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//
//                }elseif($categoryCars == 2){
//                    $CarsCaption = CarsCaption::where('category_car_id', 2)->get();
//                    $captains = CaptionActivity::whereIn('captain_id', $CarsCaption->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//                }else{
//                    $CarsCaption = CarsCaption::where('category_car_id', 3)->get();
//                    $captains = CaptionActivity::whereIn('captain_id', $CarsCaption->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//                }
//            }
//
//
//            if ($CarTypes) {
//                if ($CarTypes == 1) {
//                    $CarsCaption = CarType::where('category_car_id', 1)->get();
//                    $captains = CaptionActivity::whereIn('captain_id', $CarsCaption->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//
//                }elseif($CarTypes == 2){
//                    $CarsCaption = CarType::where('category_car_id', 2)->get();
//                    $captains = CaptionActivity::whereIn('captain_id', $CarsCaption->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//                }
//            }
//
//
//            if ($categoryCars && $CarTypes){
//                if ($categoryCars == 1  &&  $CarTypes == 1) {
//                    $CarsType = CarType::where('category_car_id', 1)->get();
//                    $CarsCaption = CarsCaption::where('category_car_id', 1)->get();
//                    $data = $CarsType->concat($CarsCaption);
//                    $captains = CaptionActivity::whereIn('captain_id', $data->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//
//                }elseif($categoryCars == 2 && $CarTypes == 2){
//                    $CarsType = CarType::where('category_car_id', 2)->get();
//                    $CarsCaption = CarsCaption::where('category_car_id', 2)->get();
//                    $data = $CarsType->concat($CarsCaption);
//                    $captains = CaptionActivity::whereIn('captain_id', $data->id)->where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *,(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
//                        ->having('distance', '<', $radius)
//                        ->orderBy('distance')
//                        ->get();
//                    return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//                }
//            }
//
//
//
//
//
//
//            $captains = CaptionActivity::where('status_captain_work', 'active')->where('status_captain', 'active')->where('type_captain', 'active')->selectRaw(" *, (6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance ")
//                ->having('distance', '<', $radius)
//                ->orderBy('distance')
//                ->get();
//
//            return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
//
//        } catch (\Exception $exception) {
//            return $this->errorResponse('Something went wrong, please try again later');
//        }
//
//
//    }



    public function captionActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        try {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = 50;
            $categoryCars = $request->category_car_id;
            $carTypes = $request->car_type_id;

            $captains = CaptionActivity::where('status_captain_work', 'active')
                ->where('status_captain', 'active')
                ->where('type_captain', 'active')
                ->selectRaw("*, (6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude)))) AS distance")
                ->having('distance', '<', $radius);

            if ($categoryCars) {
                $captains->whereIn('captain_id', CarsCaption::where('category_car_id', $categoryCars)->pluck('id'));
            }

            if ($carTypes) {
                $captains->whereIn('captain_id', CarType::whereIn('category_car_id', $carTypes)->pluck('id'));
            }

            if ($categoryCars && $carTypes) {
                $captains->whereIn('captain_id', CarType::whereIn('category_car_id', $categoryCars)->get()->concat(CarsCaption::whereIn('category_car_id', $carTypes)->get())->pluck('id'));
            }

            $captains = $captains->orderBy('distance')->get();

            return $this->successResponse(CaptionActivityUserResources::collection($captains), 'Data returned successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }
    }

}
