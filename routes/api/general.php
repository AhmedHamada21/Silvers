<?php

use App\Http\Controllers\Api\complaint\ComplaintController;

use App\Http\Controllers\Api\Orders\OrderDayController;
use App\Http\Controllers\Api\Orders\OrderHourController;
use App\Http\Controllers\Api\Orders\OrdersController;
use App\Http\Controllers\Api\Wallet\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('rest', [OrdersController::class, 'rest']);
Route::group(['prefix' => 'order', 'middleware' => 'auth:users-api,captain-api'], function () {
    Route::post('getOrder', [OrdersController::class, 'index']);
    Route::post('createOrder', [OrdersController::class, 'store']);
    Route::post('updateStatus', [OrdersController::class, 'update']);
    Route::post('takingOrder', [OrdersController::class, 'takingOrder']);
    Route::post('takingCompleted', [OrdersController::class, 'takingCompleted']);
    Route::post('canselOrder', [OrdersController::class, 'canselOrder']);
    // Deleted Orders
//    Route::post('deletedOrder', [OrdersController::class, 'deletedOrder']);
    Route::post('checkOrder', [OrdersController::class, 'checkOrder']);
    Route::get('sendNotationsCalculator', [OrdersController::class, 'sendNotationsCalculator']);
    Route::post('OrderExiting', [OrdersController::class, 'OrderExiting']);

    Route::get('getSavedOrders',[OrdersController::class, 'getSavedOrders']);

    ################# Create Orders Hours #########################
    Route::prefix('hours')->as('hours')->group(function () {
        Route::post('getOrder',[OrderHourController::class,'index']);
        Route::post('saveHours', [OrderHourController::class, 'saveHours']);
        Route::post('createOrder', [OrderHourController::class, 'store']);
        Route::post('updateStatus', [OrderHourController::class, 'update']);
        Route::post('canselOrder', [OrderHourController::class, 'canselOrder']);
    });

    ################# Create Orders Hours #########################
    Route::prefix('day')->as('day')->group(function () {
        Route::post('getOrder',[OrderDayController::class,'index']);
        Route::post('createOrder', [OrderDayController::class, 'store']);
        Route::post('saveDay', [OrderDayController::class, 'saveDay']);
        Route::post('updateStatus', [OrderDayController::class, 'update']);
        Route::post('canselOrder', [OrderDayController::class, 'canselOrder']);
    });



});


Route::group(['prefix' => 'wallet', 'middleware' => 'auth:users-api,captain-api'], function () {
    Route::post('getAmounts', [WalletController::class, 'index']);
    Route::post('createAmounts', [WalletController::class, 'store']);
});


Route::group(['prefix' => 'complaint', 'middleware' => 'auth:users-api,captain-api'], function () {
    Route::post('complaint', [ComplaintController::class, 'store']);
});
