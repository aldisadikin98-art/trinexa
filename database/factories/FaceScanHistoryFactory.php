<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FaceScanHistory>
 */
class FaceScanHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'foto_url' => fake()->imageUrl(),
            'result_json' => ['score' => fake()->numberBetween(50, 100), 'issues' => ['acne', 'dryness']],
            'tipe_kulit' => fake()->randomElement(['normal', 'dry', 'oily', 'combination']),
        ];
    }
}
