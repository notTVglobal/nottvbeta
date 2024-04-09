<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\ShowEpisode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class ShowEpisodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShowEpisode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

      $userIds = \App\Models\User::pluck('id')->all();
      $showIds = \App\Models\Show::pluck('id')->all();
      $statusIds = \App\Models\ShowEpisodeStatus::pluck('id')->all();

        return [
            'name' => $name = $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'description' => $this->faker->sentence(5),
            'image_id' => function () { return Image::factory()->create()->id; },
            'user_id' => $this->faker->randomElement($userIds),
            'show_id' => $this->faker->randomElement($showIds),
            'notes' => $this->faker->sentence(5),
            'video_url' => null,
            'video_embed_code' => null,
            'isPublished' => '0',
            'slug' => \Str::slug($name),
            'show_episode_status_id' => $this->faker->randomElement($statusIds),        ];
    }
}
