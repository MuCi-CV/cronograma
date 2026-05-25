<?php

namespace Database\Factories;

use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'type' => $this->faker->randomElement(['objetivo', 'meta']),
            'text' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 10),
            'active' => true,
        ];
    }
}
