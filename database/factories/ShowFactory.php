<?php

namespace Database\Factories;

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
        return [
            'name' => $this->faker->sentence($nbWords = 3, $variableNbWords = true),
            'description' => $this->faker->paragraph,
            'poster' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/EBU_Colorbars.svg/1280px-EBU_Colorbars.png',
            'user_id' => \App\Models\User::all()->random()->id,
            'team_id' => \App\Models\Team::all()->random()->id
        ];
    }

}
