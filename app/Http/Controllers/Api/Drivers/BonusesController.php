<?php

namespace App\Http\Controllers\Api\Drivers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Drivers\BonusResources;
use App\Models\Bonus;
use App\Models\CaptainProfile;
use App\Models\CaptionBonus;
use App\Models\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BonusesController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $data = Bonus::get();
            return $this->successResponse(BonusResources::collection($data), 'data Return Successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'captain_id' => 'required|exists:captains,id',
            'bonuses_id' => 'required|exists:bonuses,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        try {

            $data = CaptionBonus::create([
                'captain_id' => auth('captain-api')->id(),
                'bonuses_id' => $request->bonuses_id,
                'bout' => $request->bout,
                'status' => 'active',
            ]);

            if ($data) {
                return $this->successResponse('Caption Created Successfully in bonuses');
            }

        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }
    }

}
