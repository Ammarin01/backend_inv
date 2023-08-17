<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->word(3, true),
            'slug' => $this->faker->slug(2, false),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'image' => $this->faker->imageUrl(800,600),
            'user_id' => $this->faker->randomNumber(1,99)
        ];
    }
}
