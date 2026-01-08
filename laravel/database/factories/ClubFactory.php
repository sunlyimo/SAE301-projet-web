<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        return [
            'CLU_NOM' => fake()->company(),
            'CLU_RUE' => fake()->streetAddress(),
            'CLU_CODE_POSTAL' => fake()->numerify('#####'),
            'CLU_VILLE' => fake()->city(),
        ];
    }
}
