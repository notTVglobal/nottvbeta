<?php

namespace Database\Factories;

use App\Models\Episode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Episode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $name = $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'description' => $this->faker->sentence(5),
            'image_id' => '4',
            'user_id' => \App\Models\User::all()->random()->id,
            'team_id' => \App\Models\Team::all()->random()->id,
            'show_id' => \App\Models\Show::all()->random()->id,
            'slug' => \Str::slug($name),
            'notes' => ['', ''],
            'isPublished' => '0',
        ];
    }
}
