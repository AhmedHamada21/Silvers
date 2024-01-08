<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\CallCenter;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\Admin\Order;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {
    Route::group(['prefix' => 'callCenter', 'middleware' => 'auth:call-center'], function () {
        Route::get('dashboard', [CallCenter\CallCenterDashboardController::class, 'index'])->name('callCenter.dashboard');
        // Captains ::
        Route::resource('CallCenterCaptains', CallCenter\CaptainController::class);
        Route::put('/captains/{id}/updateStatus', [CallCenter\CaptainController::class, 'updateActivityStatus'])->name('CallCenterCaptains.updateActivityStatus');
        Route::post('CallCenterCaptains/upload-media', [CallCenter\CaptainController::class, 'uploadPersonalMedia'])->name('CallCenterCaptains.uploadMedia');
        Route::post('CallCenterCaptains/upload-car-media', [CallCenter\CaptainController::class, 'uploadCarMedia'])->name('CallCenterCaptains.uploadCarMedia');
        Route::post('CallCenterCaptains/update-media-status/{id}', [CallCenter\CaptainController::class, 'updatePersonalMediaStatus'])->name('CallCenterCaptains.updateMediaStatus');
        Route::post('CallCenterCaptains/update-car-status/{id}', [CallCenter\CaptainController::class, 'updateCarStatus'])->name('CallCenterCaptains.updateCarStatus');
        Route::get('CallCenterCaptains/trips/{id}', [CallCenter\CaptainController::class, 'trips'])->name('CallCenterCaptains.trips');
        Route::put('CallCenterCaptains/profile/{id}', [CallCenter\CaptainController::class, 'updateProfile'])->name('CallCenterCaptains.updateProfile');

        Route::post('captains/sendNotification/All/callCenter', [CallCenter\CaptainController::class, 'sendNotificationAll'])->name('captains.sendNotification_callCenter');
        Route::get('captains_searchNumber', [CallCenter\CaptainController::class, 'captains_searchNumber'])->name('captains.searchNumber');
        Route::controller(CallCenter\CaptainController::class)->prefix('orders')->as('callCenterOrders.')->group(function () {
            Route::get('/{order_code}', 'showOrder')->name('show');
        });
        // Order Hour ::
        Route::controller(CallCenter\CaptainController::class)->prefix('order-hour')->as('callCenterOrderHour.')->group(function () {
            Route::get('/{order_code}', 'showOrderHour')->name('show');
        });
        // Order Day ::
        Route::controller(CallCenter\CaptainController::class)->prefix('order-day')->as('callCenterOrderDay.')->group(function () {
            Route::get('/{order_code}', 'showOrderDay')->name('show');
        });
        Route::put('/call-center-captains/block/{captain}', [CallCenter\CaptainController::class, 'blockCaptain'])->name('CallCenterCaptains.block');
        // Tickets ::
        Route::resource('CallCenterTickets', CallCenter\TicketController::class);
        Route::post('CallCenterTickets/{id}/addReply', [CallCenter\TicketController::class, 'addReply'])->name('CallCenterTickets.addReply');
        Route::post('/update-ticket-status/{ticketId}', [CallCenter\TicketController::class, 'updateTicketStatus'])->name('update-ticket-status');
    
        // Car Model ::
        Route::get('/get-car-models/{carMakeId}', [CallCenter\CaptainController::class, 'getCarModelsByMakeId']);
        Route::post('createNewCar', [CallCenter\CaptainController::class, 'createNewCar'])->name('createNewCar');
        Route::get('{captain}/new-car', [CallCenter\CaptainController::class, 'newCar'])->name('CallCenterCaptains.captainNewCar');
        // Users ::
        Route::resource('CallCenterUsers', CallCenter\UserController::class);
        Route::post('users/sendNotification/All/callCenter', [CallCenter\UserController::class, 'sendNotificationAll'])->name('users.sendNotification_callCenter');
    
        // Colors ::
        Route::resource('Colors', CallCenter\ColorController::class);
    });
});