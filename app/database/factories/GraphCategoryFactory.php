<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GraphCategory>
 */
class GraphCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {

        return [
            "title"        => $this->faker->name,
            "color_title"  => "#FFFFFF",
            "color_border" => "#FFFFFF",
            "order"        => 1,
        ];
    }
}
