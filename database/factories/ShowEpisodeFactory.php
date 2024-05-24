<?php

namespace Database\Factories;

use App\Models\CreativeCommons;
use App\Models\Image;
use App\Models\Show;
use App\Models\ShowEpisode;
use App\Models\ShowEpisodeStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShowEpisodeFactory extends Factory {
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
  public function definition(): array {
    // Fetch all necessary IDs
    $userIds = User::pluck('id')->all();
    $statusIds = ShowEpisodeStatus::pluck('id')->all();
    $creativeCommonsIds = CreativeCommons::pluck('id')->all();

    // Pluck a random status ID
    $statusId = $this->faker->randomElement($statusIds);

    // Base attributes
    $attributes = [
        'name' => $name = $this->faker->sentence(3, true),
        'description' => $this->faker->sentence(5),
        'image_id' => Image::factory()->create()->id,
        'user_id' => $this->faker->randomElement($userIds),
        'show_id' => Show::factory(), // Default show_id for factory, will be overridden by seeder
        'notes' => $this->faker->sentence(5),
        'video_url' => null,
        'video_embed_code' => null,
        'isPublished' => 0,
        'release_year' => null,
        'release_dateTime' => null,
        'scheduled_release_dateTime' => null,
        'copyrightYear' => null,
        'slug' => Str::slug($name),
        'show_episode_status_id' => $statusId,
        'creative_commons_id' => $this->faker->randomElement($creativeCommonsIds),
    ];

    // Add status-specific attributes
    return $this->applyStatusAttributes($attributes, $statusId);
  }


  /**
   * Apply status-specific attributes.
   *
   * @param array $attributes
   * @param int $statusId
   * @return array
   */
  protected function applyStatusAttributes(array $attributes, int $statusId): array {
    if ($statusId == 7) {
      $releaseDateTime = $this->generateRandomDateTimeInPast();
      $attributes['isPublished'] = 1;
      $attributes['release_dateTime'] = $releaseDateTime;
      $attributes['release_year'] = $releaseDateTime->year;
      $attributes['copyrightYear'] = $releaseDateTime->year;
      $attributes['scheduled_release_dateTime'] = null;
    } elseif ($statusId == 6) {
      $scheduledReleaseDateTime = $this->generateRandomDateTimeInFuture();
      $attributes['isPublished'] = 0;
      $attributes['release_dateTime'] = null;
      $attributes['release_year'] = null;
      $attributes['copyrightYear'] = $scheduledReleaseDateTime->year;
      $attributes['scheduled_release_dateTime'] = $scheduledReleaseDateTime;
    }

    return $attributes;
  }

  /**
   * Generate a random past date and time on the hour or half hour.
   *
   * @return Carbon
   */
  protected function generateRandomDateTimeInPast(): Carbon {
    $dateTime = Carbon::now()->subDays(rand(1, 365));
    $dateTime->minute(rand(0, 1) * 30);
    $dateTime->second(0);

    return $dateTime;
  }

  /**
   * Generate a random future date and time on the hour or half hour.
   *
   * @return Carbon
   */
  protected function generateRandomDateTimeInFuture(): Carbon {
    $dateTime = Carbon::now()->addDays(rand(1, 365));
    $dateTime->minute(rand(0, 1) * 30);
    $dateTime->second(0);

    return $dateTime;
  }
}
