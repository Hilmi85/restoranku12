<?php

namespace Database\Factories;

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
            'name' => $this->faker->word,
            'category_id' => $this->faker->numberBetween(1, 2), // Assuming categories with IDs 1 and 2 exist
            'price' => $this->faker->randomFloat(2,1000, 100000),
            'description' => $this->faker->text(),
            'img' => fake()->randomElement([
                'https://images.unsplash.com/photo-1591325418441-ff678baf78ef',
                'https://plus.unsplash.com/premium_photo-1668146927669-f2edf6e86f6f',
                'https://images.unsplash.com/photo-1513104890138-7c749659a591',
                'https://images.unsplash.com/photo-1611270629569-8b357cb88da9',
            ]),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
