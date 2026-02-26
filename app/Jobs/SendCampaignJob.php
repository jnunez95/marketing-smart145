<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Campaign $campaign
    ) {}

    public function handle(): void
    {
        $this->campaign->update(['status' => Campaign::STATUS_SENDING]);

        $query = $this->campaign->group_id
            ? $this->campaign->group->stations()
            : \App\Models\Station::query();

        $query->whereNotNull('email')->where('email', '!=', '');

        $count = 0;
        $query->chunk(50, function ($stations) use (&$count): void {
            foreach ($stations as $station) {
                $log = CampaignLog::create([
                    'campaign_id' => $this->campaign->id,
                    'station_id' => $station->id,
                    'email' => $station->email,
                    'status' => CampaignLog::STATUS_PENDING,
                    'tracking_token' => (string) Str::uuid(),
                ]);
                SendCampaignEmailJob::dispatch($this->campaign, $station, $log);
                $count++;
            }
        });

        if ($count === 0) {
            $this->campaign->update([
                'status' => $this->campaign->scheduled_at ? Campaign::STATUS_SCHEDULED : Campaign::STATUS_DRAFT,
                'total_recipients' => 0,
            ]);

            return;
        }

        $this->campaign->update([
            'total_recipients' => $count,
            'status' => Campaign::STATUS_SENT,
            'sent_at' => now(),
        ]);
    }
}
