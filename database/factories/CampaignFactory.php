<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'email_template_id' => EmailTemplate::factory(),
            'group_id' => null,
            'scheduled_at' => null,
            'sent_at' => null,
            'status' => Campaign::STATUS_DRAFT,
            'total_recipients' => 0,
            'total_sent' => 0,
            'total_opened' => 0,
            'total_clicked' => 0,
            'created_by' => User::factory(),
        ];
    }
}
