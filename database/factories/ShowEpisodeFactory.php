<?php

namespace Database\Factories;

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
        return [
            'name' => $name = $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'description' => $this->faker->sentence(5),
            'image_id' => '4',
            'user_id' => \App\Models\User::all()->random()->id,
            'show_id' => \App\Models\Show::all()->random()->id,
            'notes' => $this->faker->sentence(5),
            'isPublished' => '0',
            'slug' => \Str::slug($name)
        ];
    }
}
