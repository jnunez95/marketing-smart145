<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use Illuminate\Console\Command;

class ProcessScheduledCampaignsCommand extends Command
{
    protected $signature = 'campaigns:process-scheduled';

    protected $description = 'Process and send scheduled campaigns';

    public function handle(): int
    {
        $campaigns = Campaign::where('status', Campaign::STATUS_SCHEDULED)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($campaigns as $campaign) {
            SendCampaignJob::dispatch($campaign);
        }

        $this->info('Dispatched '.$campaigns->count().' scheduled campaign(s).');

        return self::SUCCESS;
    }
}
