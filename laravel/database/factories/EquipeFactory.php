<?php

namespace Database\Factories;

use App\Models\Equipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipeFactory extends Factory
{
    protected $model = Equipe::class;

    public function definition(): array
    {
        return [
            'RAI_ID' => null, // Doit être fourni
            'COU_ID' => null, // Doit être fourni
            'EQU_ID' => fake()->unique()->numberBetween(1, 9999),
            'UTI_ID' => User::factory(),
            'EQU_NOM' => fake()->words(2, true),
            'EQU_IMAGE' => null,
        ];
    }
}
