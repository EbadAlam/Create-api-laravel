<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment_user_id' => User::factory(),
            'comment_post_id' => Post::factory(),
            'comment_content' => $this->faker->text,
            // 'comment_status' => $this->faker->randomElement(['Approve','Unaprrove']),
        ];
    }
}
