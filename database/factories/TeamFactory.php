<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      $userIds = \App\Models\User::pluck('id')->all();

      return [
            'name' => $name = $this->faker->sentence($nbWords = 2, $variableNbWords = true),
            'description' => $this->faker->paragraph,
            'user_id' => $this->faker->randomElement($userIds),
            'image_id' => '3',
            'slug' => \Str::slug($name)
        ];
    }
}
