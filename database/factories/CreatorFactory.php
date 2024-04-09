<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Creator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Creator>
 */
class CreatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Creator::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Look at https://laravel.com/docs/9.x/eloquent-factories
        // the TeamMember Pivot controller needs to be changed from
        // Users to Creators.
        //
        return [
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
