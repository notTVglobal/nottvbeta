<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class ShowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Show::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      $userIds = \App\Models\User::pluck('id')->all();
      $teamIds = \App\Models\Team::pluck('id')->all();

        return [
            'name' => $name = $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'description' => $this->faker->paragraph,
            'image_id' => function () { return Image::factory()->create()->id; },
            'user_id' => $this->faker->randomElement($userIds),
            'team_id' => $this->faker->randomElement($teamIds),
            'show_status_id' => \App\Models\ShowStatus::all()->random()->id,
            'slug' => \Str::slug($name)
        ];
    }

}
