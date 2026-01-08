<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UTI_NOM' => fake()->lastName(),
            'UTI_PRENOM' => fake()->firstName(),
            'UTI_EMAIL' => fake()->unique()->safeEmail(),
            'UTI_DATE_NAISSANCE' => fake()->date(),
            'UTI_MOT_DE_PASSE' => static::$password ??= Hash::make('password'),
            'UTI_NOM_UTILISATEUR' => fake()->userName(),
            'UTI_RUE' => fake()->streetAddress(),
            'UTI_CODE_POSTAL' => fake()->numerify('#####'),  // 5 chiffres max (limite = 6)
            'UTI_VILLE' => fake()->city(),
            'UTI_TELEPHONE' => fake()->numerify('##########'),  // 10 chiffres exactement
            'UTI_LICENCE' => fake()->optional()->numerify('LIC#####'),  // 8 caractères max
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'UTI_DATE_NAISSANCE' => null,
        ]);
    }
}
