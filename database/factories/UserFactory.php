<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->name(),
            'user_firstname' => $this->faker->name(),
            'user_lastname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'user_image' => $this->faker->imageUrl($width = 640, $height = 480),
            'user_role' => $this->faker->randomElement(['Admin','Subscriber']),
            'password' => Hash::make(123456),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // public function unverified()
    // {
    //     return $this->state(function (array $attributes) {
    //         return [
    //             'email_verified_at' => null,
    //         ];
    //     });
    // }
}
