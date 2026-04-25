<?php

namespace Database\Factories;

use App\Models\Novel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
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
            'novel_id' => Novel::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->sentence(),
        ];
    }
}
