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
        Route::post('CallCenterCaptains/upload-media', [CallCenter\CaptainController::class, 'uploadPersonalMedia'])->name('CallCenterCaptains.uploadMedia');
        Route::post('CallCenterCaptains/upload-car-media', [CallCenter\CaptainController::class, 'uploadCarMedia'])->name('CallCenterCaptains.uploadCarMedia');
        Route::post('CallCenterCaptains/update-media-status/{id}', [CallCenter\CaptainController::class, 'updatePersonalMediaStatus'])->name('CallCenterCaptains.updateMediaStatus');
        Route::post('CallCenterCaptains/update-car-status/{id}', [Admin\CaptainController::class, 'updateCarStatus'])->name('CallCenterCaptains.updateCarStatus');
    });
});