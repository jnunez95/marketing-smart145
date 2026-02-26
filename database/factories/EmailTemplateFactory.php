<?php

namespace Database\Factories;

use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmailTemplate>
 */
class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    public function definition(): array
    {
        return [
            'name'       => fake()->sentence(3),
            'subject'    => fake()->sentence(),
            'body_html'  => '<p>'.fake()->paragraphs(3, true).'</p>',
            'body_plain' => fake()->paragraphs(3, true),
            'variables'  => ['agency_name', 'email', 'accountable_manager'],
            'is_active'  => true,
        ];
    }
}
