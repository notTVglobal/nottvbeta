<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
  protected $model = Image::class;

  /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      $userIds = User::pluck('id')->all();

      return [
          'name' => $this->faker->word,
          'extension' => 'jpg',
          'size' => $this->faker->numberBetween(500, 5000),
          'user_id' => $this->faker->randomElement($userIds),
          'placeholder_url' => 'https://picsum.photos/id/'.$this->faker->numberBetween(1, 300).'/200/300', // Replace with your desired source
        // Add other fields as required
      ];
    }
}
