<?php

use App\Http\Controllers\Dashboard\CallCenter;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

        Route::post('captains/sendNotification/All/callCenter', [CallCenter\CaptainController::class, 'sendNotificationAll'])->name('captains.sendNotification_callCenter');
        Route::get('captains_searchNumber', [CallCenter\CaptainController::class, 'captains_searchNumber'])->name('captains.searchNumber');

    });
});
