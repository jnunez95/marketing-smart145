<?php

use App\Http\Controllers\PostmarkWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalWebhookController;

Route::post('/webhooks/postmark', PostmarkWebhookController::class)
    ->middleware(['postmark.webhook', 'throttle:120,1'])
    ->name('webhooks.postmark');

Route::post('/webhooks/cal', CalWebhookController::class)
    ->name('webhooks.postmark');
