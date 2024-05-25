<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChannelPlaylist;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChannelPlaylist>
 */
class ChannelPlaylistFactory extends Factory
{
  protected $model = ChannelPlaylist::class;

  public function definition()
  {

    return [
        'channel_id' => \App\Models\Channel::inRandomOrder()->first()->id,
        'name' => $this->faker->word() . '_' . uniqid(),
        'description' => $this->faker->sentence,
        'url' => $this->faker->url,
        'type' => $this->faker->randomElement(['Regular', 'Event', 'Special']), // Adjust as per your actual types
        'status' => $this->faker->boolean,
        'start_dateTime' => $this->faker->dateTime,
        'end_dateTime' => $this->faker->dateTime,
        'priority' => $this->faker->numberBetween(1, 10),
        'repeat_mode' => $this->faker->boolean,
    ];
  }
}
