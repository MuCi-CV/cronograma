<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stage>
 */
class StageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Etapa ' . $this->faker->numberBetween(1, 3),
            'year' => $this->faker->numberBetween(2026, 2028),
            'order' => $this->faker->numberBetween(1, 3),
        ];
    }
}
