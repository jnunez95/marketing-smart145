<?php

use App\Http\Controllers\PostmarkWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/postmark', PostmarkWebhookController::class)
    ->middleware(['postmark.webhook', 'throttle:120,1'])
    ->name('webhooks.postmark');
