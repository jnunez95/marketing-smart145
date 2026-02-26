<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignJob;
use App\Models\CampaignSchedule;
use Illuminate\Console\Command;

class ProcessScheduledCampaignsCommand extends Command
{
    protected $signature = 'campaigns:process-scheduled';

    protected $description = 'Process and send scheduled campaigns';

    public function handle(): int
    {
        $schedules = CampaignSchedule::with('campaign')
            ->whereNull('sent_at')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at')
            ->get();

        $count = 0;
        foreach ($schedules as $schedule) {
            SendCampaignJob::dispatch($schedule->campaign);
            $schedule->update(['sent_at' => now()]);
            $count++;
        }

        if ($count > 0) {
            $this->info('Dispatched '.$count.' scheduled send(s).');
        }

        return self::SUCCESS;
    }
}
