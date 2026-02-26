<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Station>
 */
class StationFactory extends Factory
{
    protected $model = Station::class;

    public function definition(): array
    {
        return [
            'agency_name'               => fake()->company(),
            'dsgn_code'                 => fake()->optional()->regexify('[A-Z]{2}[0-9]{4}'),
            'dba'                       => fake()->optional()->company(),
            'cert_no'                   => fake()->unique()->numerify('CERT-####'),
            'address_line_1'            => fake()->streetAddress(),
            'address_line_2'            => fake()->optional()->secondaryAddress(),
            'address_line_3'            => null,
            'city'                      => fake()->city(),
            'state_province'            => fake()->state(),
            'country'                   => fake()->country(),
            'postal_code'               => fake()->postcode(),
            'phone'                     => fake()->phoneNumber(),
            'email'                     => fake()->companyEmail(),
            'accountable_manager'       => fake()->name(),
            'accountable_manager_phone' => fake()->phoneNumber(),
            'accountable_manager_email' => fake()->email(),
            'liaison'                   => fake()->name(),
            'liaison_phone'             => fake()->phoneNumber(),
            'liaison_email'             => fake()->email(),
            'rating_accessory'          => null,
            'rating_airframe'           => null,
            'rating_instrument'         => null,
            'rating_limited'            => null,
            'rating_powerplant'         => null,
            'rating_propeller'          => null,
            'rating_radio'              => null,
            'bilateral_agreements'      => null,
            'image_path'                => null,
            'latitude'                  => fake()->latitude(),
            'longitude'                 => fake()->longitude(),
            'updated_at_source'         => fake()->optional()->date(),
            'group_id'                  => Group::factory(),
        ];
    }
}
