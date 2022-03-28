<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_category_id' => Category::factory(),
            'post_title' => $this->faker->name(),
            'post_author' => $this->faker->name(),
            'post_date' => $this->faker->date($format = 'Y m d h:i:s',$max = 'now'),
            'post_image' => $this->faker->imageUrl($width = 640, $height = 480),
            'post_content' => $this->faker->text,
            'post_tags' => $this->faker->name(),
            'post_status' => $this->faker->randomElement(['Publish','Draft'])
        ];
    }
}
