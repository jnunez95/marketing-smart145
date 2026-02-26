<?php

use App\Models\Campaign;
use App\Models\CampaignSchedule;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Campaign::whereNotNull('scheduled_at')
            ->whereDoesntHave('campaignSchedules')
            ->each(function (Campaign $campaign): void {
                CampaignSchedule::create([
                    'campaign_id'  => $campaign->id,
                    'scheduled_at' => $campaign->scheduled_at,
                    'sent_at'      => $campaign->status === Campaign::STATUS_SENT ? $campaign->sent_at : null,
                ]);
            });
    }

    public function down(): void
    {
        // Optional: remove schedules that were created from legacy scheduled_at
        // Left no-op to avoid data loss
    }
};
