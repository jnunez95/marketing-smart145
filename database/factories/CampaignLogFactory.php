<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\Campaign;
use App\Models\CampaignLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignLog>
 */
class CampaignLogFactory extends Factory
{
    protected $model = CampaignLog::class;

    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'station_id' => Station::factory(),
            'email' => fake()->email(),
            'status' => CampaignLog::STATUS_PENDING,
            'opened_at' => null,
            'clicked_at' => null,
            'error_message' => null,
            'tracking_token' => str()->uuid()->toString(),
        ];
    }
}
