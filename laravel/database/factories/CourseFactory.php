<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Raid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'RAI_ID' => null, // Doit être fourni lors de la création
            'COU_ID' => fake()->unique()->numberBetween(1, 9999),
            'TYP_ID' => fake()->numberBetween(1, 5),
            'DIF_NIVEAU' => fake()->numberBetween(1, 5),
            'UTI_ID' => User::factory(),
            'COU_NOM' => fake()->words(2, true),
            'COU_DATE_DEBUT' => fake()->dateTime(),
            'COU_DATE_FIN' => fake()->dateTime(),
            'COU_PRIX' => fake()->randomFloat(2, 10, 100),
            'COU_LIEU' => fake()->city(),
            'COU_AGE_MIN' => fake()->numberBetween(10, 18),
            'COU_AGE_SEUL' => fake()->numberBetween(16, 18),
            'COU_AGE_ACCOMPAGNATEUR' => fake()->numberBetween(18, 21),
            'COU_PARTICIPANT_MIN' => fake()->numberBetween(2, 5),
            'COU_PARTICIPANT_MAX' => fake()->numberBetween(20, 50),
            'COU_EQUIPE_MIN' => fake()->numberBetween(1, 5),
            'COU_EQUIPE_MAX' => fake()->numberBetween(10, 30),
            'COU_PARTICIPANT_PAR_EQUIPE_MAX' => fake()->numberBetween(4, 8),
            'COU_REDUCTION' => fake()->optional()->randomFloat(2, 0, 50),
            'COU_PRIX_ENFANT' => fake()->optional()->randomFloat(2, 5, 50),
            'COU_REPAS_PRIX' => fake()->optional()->randomFloat(2, 5, 20),
        ];
    }
}
