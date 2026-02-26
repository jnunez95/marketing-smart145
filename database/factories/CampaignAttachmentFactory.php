<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\CampaignAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignAttachment>
 */
class CampaignAttachmentFactory extends Factory
{
    protected $model = CampaignAttachment::class;

    public function definition(): array
    {
        $name = fake()->word().'.pdf';
        return [
            'campaign_id' => Campaign::factory(),
            'file_path' => 'campaigns/'.fake()->uuid().'/'.$name,
            'file_name' => $name,
            'file_size' => fake()->numberBetween(1000, 5000000),
            'mime_type' => 'application/pdf',
        ];
    }
}
