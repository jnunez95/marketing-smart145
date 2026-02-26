<?php

namespace App\Http\Controllers;

use App\Models\CalWebhookEvent;
use App\Models\CampaignLog;
use App\Models\EmailEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CalWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        Log::info($request->all());

        $data = $request->all();

        CalWebhookEvent::create([
            'trigger_event' => $data['triggerEvent'],
            'booking_uid'   => $data['payload']['uid'] ?? null,
            'payload'       => $data['payload'],
            'status'        => CalWebhookEvent::STATUS_RECEIVED,
            'event_time'    => Carbon::parse($data['createdAt']),
        ]);

        return response()->json(['ok' => true], 200);
    }
}
