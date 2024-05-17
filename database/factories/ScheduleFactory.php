<?php

namespace Database\Factories;

use App\Models\MistStream;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Show;
use App\Models\Movie;
use App\Models\ShowEpisode;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory {
  protected $year;
  protected $month;
  protected $day;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition() {
    static $hourIncrement = 0; // Keep track of the hour increment for each item

    // Use provided $year, $month, and $day, defaulting to current date if not set
    $year = $this->year ?? now()->year;
    $month = $this->month ?? now()->month;
    $day = $this->day ?? now()->day;

    // Initialize start time at 00:00 and add hours based on the increment
    $startTime = Carbon::create($year, $month, $day, 0, 0)->addHours($hourIncrement++);

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

    // Calculate the end time based on the duration
    $endTime = $startTime->copy()->addMinutes($duration);

    return [
        'content_type'     => $contentType,
        'content_id'       => $contentId,
        'start_time'       => $startTime,
        'duration_minutes' => $duration,
        'end_time'         => $endTime,
        'type'             => $contentType ? lcfirst(class_basename($contentType)) : null,
        'status'           => 'scheduled',
        'priority'         => 5, // Set priority to 5
        'recurrence_flag'  => 0
    ];
  }

  /**
   * Allow setting custom start date for the schedule.
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @return $this
   */
  public function withStartDate($year, $month, $day) {
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;

    return $this;
  }

}
