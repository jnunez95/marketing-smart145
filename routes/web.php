<?php

use App\Http\Controllers\PublicStatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stats', [PublicStatsController::class, 'index'])->name('stats.index');
