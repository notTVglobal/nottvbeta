<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\Image;
use App\Models\Show;
use App\Models\ShowCategory;
use App\Models\ShowCategorySub;
use App\Models\ShowStatus;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
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
    // Fetch all necessary IDs
    $showCategoryIds = ShowCategory::pluck('id')->all();

    // Randomly select a show category
    $showCategoryId = $this->faker->randomElement($showCategoryIds);

    // Fetch subcategories that belong to the selected show category
    $showCategorySubIds = ShowCategorySub::where('show_categories_id', $showCategoryId)->pluck('id')->all();

    return [
        'name' => $name = $this->faker->sentence(3, true),
        'description' => $this->faker->paragraph,
        'image_id' => Image::factory()->create()->id,
        'user_id' => null, // This will be set in the state method
        'team_id' => Team::factory(), // Default team_id for factory, will be overridden by seeder
        'show_status_id' => ShowStatus::all()->random()->id,
        'slug' => Str::slug($name),
        'show_category_id' => $showCategoryId,
        'show_category_sub_id' => !empty($showCategorySubIds) ? $this->faker->randomElement($showCategorySubIds) : null,
        'show_runner' => null, // This will be set in the state method
        'www_url' => $this->faker->url,
        'instagram_name' => $this->faker->userName,
        'telegram_url' => $this->faker->url,
        'twitter_handle' => $this->faker->userName,
    ];
  }

  /**
   * Indicate that the show belongs to a specific team.
   *
   * @param int $teamId
   * @return \Illuminate\Database\Eloquent\Factories\Factory
   */
  public function forTeam($teamId)
  {
    return $this->state(function (array $attributes) use ($teamId) {
      $userIds = TeamMember::where('team_id', $teamId)->pluck('user_id')->all();
      $creatorIdsForTeam = Creator::whereHas('teams', function ($query) use ($teamId) {
        $query->where('team_id', $teamId);
      })->pluck('id')->all();

      return [
          'team_id' => $teamId,
          'user_id' => !empty($userIds) ? $this->faker->randomElement($userIds) : User::factory()->create()->id,
          'show_runner' => !empty($creatorIdsForTeam) ? $this->faker->randomElement($creatorIdsForTeam) : null,
      ];
    });
  }

}
