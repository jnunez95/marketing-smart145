<?php

namespace App\Http\Controllers;

use App\Models\CampaignLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CampaignTrackingController extends Controller
{
    public function open(string $token): Response
    {
        $log = CampaignLog::where('tracking_token', $token)->first();
        if ($log && $log->status !== CampaignLog::STATUS_OPENED) {
            $log->update([
                'status' => CampaignLog::STATUS_OPENED,
                'opened_at' => $log->opened_at ?? now(),
            ]);
            $log->campaign->increment('total_opened');
        }

        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($pixel, 200, [
            'Content-Type' => 'image/gif',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function click(Request $request, string $token): RedirectResponse
    {
        $url = $request->query('url', '/');
        $log = CampaignLog::where('tracking_token', $token)->first();
        if ($log) {
            if ($log->status !== CampaignLog::STATUS_CLICKED) {
                $log->update([
                    'status' => CampaignLog::STATUS_CLICKED,
                    'clicked_at' => $log->clicked_at ?? now(),
                ]);
                $log->campaign->increment('total_clicked');
            }
        }

        return redirect()->away($url);
    }
}
