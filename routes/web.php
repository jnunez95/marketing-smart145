<?php

use App\Http\Controllers\CampaignTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/track/open/{token}', [CampaignTrackingController::class, 'open'])->name('campaign.track.open');
Route::get('/track/click/{token}', [CampaignTrackingController::class, 'click'])->name('campaign.track.click');
