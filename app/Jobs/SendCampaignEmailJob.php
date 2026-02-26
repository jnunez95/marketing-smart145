<?php

namespace App\Jobs;

use App\Models\Agency;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Services\TemplateVariableParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Campaign $campaign,
        public Agency $agency,
        public CampaignLog $campaignLog
    ) {}

    public function handle(): void
    {
        $template = $this->campaign->emailTemplate;
        if (! $template) {
            $this->campaignLog->update([
                'status' => CampaignLog::STATUS_FAILED,
                'error_message' => 'Campaign has no email template.',
            ]);
            return;
        }

        $subject = TemplateVariableParser::replace($template->subject, $this->agency);
        $bodyHtml = TemplateVariableParser::replace($template->body_html, $this->agency);

        $trackingToken = $this->campaignLog->tracking_token;
        $openTrackingUrl = route('campaign.track.open', ['token' => $trackingToken]);
        $trackingPixel = '<img src="'.$openTrackingUrl.'" width="1" height="1" alt="" style="display:none" />';
        $bodyHtml = str_replace('</body>', $trackingPixel.'</body>', $bodyHtml);
        if (! str_contains($bodyHtml, '</body>')) {
            $bodyHtml .= $trackingPixel;
        }

        try {
            Mail::html($bodyHtml, function ($message) use ($subject): void {
                $message->to($this->agency->email)
                    ->subject($subject);
            });

            $this->campaignLog->update(['status' => CampaignLog::STATUS_SENT]);
            $this->campaign->increment('total_sent');
        } catch (\Throwable $e) {
            $this->campaignLog->update([
                'status' => CampaignLog::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
