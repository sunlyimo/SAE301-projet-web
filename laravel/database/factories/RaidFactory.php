<?php

namespace Database\Factories;

use App\Models\Raid;
use App\Models\User;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

class RaidFactory extends Factory
{
    protected $model = Raid::class;

    public function definition(): array
    {
        return [
            'RAI_NOM' => fake()->words(3, true),
            'RAI_RAID_DATE_DEBUT' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'RAI_RAID_DATE_FIN' => fake()->dateTimeBetween('+6 months', '+1 year'),
            'RAI_INSCRI_DATE_DEBUT' => fake()->dateTimeBetween('now', '+1 month'),
            'RAI_INSCRI_DATE_FIN' => fake()->dateTimeBetween('+1 month', '+2 months'),
            'RAI_CONTACT' => fake()->email(),
            'RAI_WEB' => fake()->url(),
            'RAI_LIEU' => fake()->city(),
            'RAI_IMAGE' => null,
            'UTI_ID' => User::factory(),
            'CLU_ID' => null,
        ];
    }
}
