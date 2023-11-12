<?php

namespace App\Http\Controllers\Api\Drivers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Drivers\CaptionResources;
use App\Models\Captain;
use App\Models\CaptionActivity;
use App\Models\Traits\Api\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;


class DriverAuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:captain-api', ['except' => ['refresh', 'checkPhone', 'login', 'register', 'login_phone', 'restPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {

            return $this->errorResponse($validator->errors(), 422);
        }
        if (!$token = auth('captain-api')->attempt($validator->validated(), ['exp' => Carbon::now()->addDays(7300)->timestamp])) {
            return $this->errorResponse('Unauthorized', 422);
        }

        if (isset($request->fcm_token)) {
            $information = Captain::where('email', $request->email)->first();
            $information->update([
                'fcm_token' => $request->fcm_token,
            ]);
        }


        $information2 = Captain::where('email', $request->email)->first();
        $information2->update([
            'fcm_token' => $request->fcm_token,
        ]);

        DB::table('personal_access_tokens')->updateOrInsert([
            'tokenable_id' => $information2->id,
            'tokenable_type' => 'App\Models\Captain',
        ], [
            'tokenable_type' => 'App\Models\Captain',
            'tokenable_id' => $information2->id,
            'name' => $information2->name,
            'token' => $token,
            'expires_at' => auth('captain-api')->factory()->getTTL() * 60000,
        ]);

        return $this->createNewToken($token);
    }

    public function login_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {

            return $this->errorResponse($validator->errors(), 422);
        }
        if (!$token = auth('captain-api')->attempt($validator->validated(), ['exp' => Carbon::now()->addDays(7300)->timestamp])) {
            return $this->errorResponse('Unauthorized', 422);
        }
        if (isset($request->fcm_token)) {
            $information = Captain::where('phone', $request->phone)->first();
            $information->update([
                'fcm_token' => $request->fcm_token,
            ]);
        }

        $information2 = Captain::where('phone', $request->phone)->first();
        DB::table('personal_access_tokens')->updateOrInsert([
            'tokenable_id' => $information2->id,
            'tokenable_type' => 'App\Models\Captain',
        ], [
            'tokenable_type' => 'App\Models\Captain',
            'tokenable_id' => $information2->id,
            'name' => $information2->name,
            'token' => $token,
            'expires_at' => auth('captain-api')->factory()->getTTL() * 60000,
        ]);


        return $this->createNewToken($token);
    }


    public function loginPhoneToken($phone)
    {
        $information = Captain::where('phone', $phone)->first();

        if (!$information) {
            return $this->errorResponse('Unauthorized', 422);
        }


        $token = auth('captain-api')->login($information);
        $information2 = Captain::where('phone', $phone)->first();
        DB::table('personal_access_tokens')->updateOrInsert([
            'tokenable_id' => $information2->id,
            'tokenable_type' => 'App\Models\Captain',
        ], [
            'tokenable_type' => 'App\Models\Captain',
            'tokenable_id' => $information2->id,
            'name' => $information2->name,
            'token' => $token,
            'expires_at' => auth('captain-api')->factory()->getTTL() * 60000,
        ]);
        return $this->createNewToken($token);
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:captains',
            'phone' => 'required|numeric|unique:captains',
            'gender' => 'required|in:male,female',
            'country_id' => 'required|exists:countries,id',
            'password' => 'required|string|min:6',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        $user = Captain::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'admin_id' => 1,
            ]
        ));

        return $this->login_phone($request);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Captain::findorfail(auth('captain-api')->id())->update([
            'fcm_token' => null,
        ]);
        CaptionActivity::where('captain_id', auth('captain-api')->id())->update([
            'status_captain' => 'inactive'
        ]);
        auth('captain-api')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh($id)
    {
        $user = Captain::findorfail($id);
        return $this->loginPhoneToken($user->phone);
//        $oldToken = auth('captain-api')->getToken();
//        if ($oldToken) {
//            $token = $oldToken->get();
//            $tokens = DB::table('personal_access_tokens')->where('token', $token)->first();
//            return $this->createNewTokenRefresh($tokens);
//        }
    }


    public function createNewTokenRefresh($token)
    {
        $users = Captain::findorfail($token->tokenable_id);
        return $this->loginPhoneToken($users->phone);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return $this->successResponse(new CaptionResources(auth('captain-api')->user()), 'data return successfully');
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('captain-api')->factory()->getTTL() * 600000, // تم تحديث هذا السطر
            'user' => new CaptionResources(auth('captain-api')->user())
        ]);
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }
        $user = Captain::where('id', auth('captain-api')->id())->first();


        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->successResponse('', 'Change password success');
        }
        return $this->errorResponse('Error');
    }


    public function restPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:captains,phone',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        $checkUser = Captain::where('phone', $request->phone)->first();
        if (!$checkUser) {
            return $this->errorResponse('The Captain Not Find');
        }

        $checkUser->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->successResponse('', 'Successfully Rest Password');
    }


    public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        $checkUser = Captain::where('phone', $request->phone)->first();

        if (!$checkUser) {
            return $this->errorResponse('The User Not Find');
        }

        return $this->successResponse(new CaptionResources($checkUser), 'User Already Expecting');
    }


    public function deleted(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        try {
            $user = Captain::findorfail(auth('captain-api')->id());

            if (Hash::check($request->password, $user->password)) {
                DB::table('personal_access_tokens')->where('tokenable_type','App\Models\Captain')->where('tokenable_id',$user->id)->delete();
                $user->delete();
                return $this->successResponse('', 'Deleted Captain Successfully');
            }
            return $this->errorResponse('Password Error', 400);
        } catch (\Exception $exception) {
            return $this->errorResponse('Something went wrong, please try again later');
        }


    }
}
