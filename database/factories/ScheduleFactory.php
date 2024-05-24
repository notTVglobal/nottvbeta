<?php

namespace Database\Factories;

use App\Models\MistStream;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Show;
use App\Models\Movie;
use App\Models\ShowEpisode;
use Carbon\Carbon;

/**
 * Define the model's default state.
 *
 * @extends Factory<Schedule>
 */

class ScheduleFactory extends Factory
{
  protected $model = Schedule::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    // Determine whether this schedule is for a show, show episode, or a movie
    $contentType = $this->faker->randomElement(['App\Models\Show', 'App\Models\ShowEpisode', 'App\Models\Movie']);
    $contentId = null;
    $durationOptions = [];

    if ($contentType === 'App\Models\Show' || $contentType === 'App\Models\ShowEpisode') {
      $durationOptions = [30, 60, 90, 120, 150, 180]; // Duration options for show or show episode

      if ($contentType === 'App\Models\Show') {
        $show = Show::whereIn('show_status_id', [1, 2])->inRandomOrder()->first();
        if ($show) {
          $contentId = $show->id;
        }
      } else {
        $showEpisode = ShowEpisode::where('show_episode_status_id', 7)
            ->whereHas('show', function ($query) {
              $query->whereIn('show_status_id', [1, 2]);
            })
            ->inRandomOrder()
            ->first();
        if ($showEpisode) {
          $contentId = $showEpisode->id;
        }
      }
    } else {
      $durationOptions = [60, 90, 120, 150, 180]; // Duration options for movies

      $movie = Movie::inRandomOrder()->first();
      if ($movie) {
        $contentId = $movie->id;
      }
    }

    // Randomly select a duration from the available options
    $duration = $this->faker->randomElement($durationOptions);

    return [
        'content_type' => $contentType,
        'content_id' => $contentId,
        'duration_minutes' => $duration,
        'type' => $contentType ? lcfirst(class_basename($contentType)) : null,
        'status' => 'scheduled',
        'priority' => 5, // Set priority to 5
    ];
  }
}
