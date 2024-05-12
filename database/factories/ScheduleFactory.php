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

    // Calculate the start time at the top of the current (or most recent) hour, then increment
//    $startTime = Carbon::now()->startOfHour()->addHours($hourIncrement++);

    // Determine whether this schedule is for a show or a movie
    $isShow = $this->faker->boolean;
    $contentId = null;
    $contentType = null;
    //  $startTime = now(); // Adjust based on your logic

    if ($isShow) {
      // Attempt to get a random published ShowEpisode where the Show is new or active
      $showEpisode = ShowEpisode::where('show_episode_status_id', 7)
          ->whereHas('show', function ($query) {
            $query->whereIn('show_status_id', [1, 2]);
          })
          ->inRandomOrder()
          ->first();

      if ($showEpisode) {
        $contentId = $showEpisode->id;
        $contentType = 'App\Models\ShowEpisode'; // Use the fully qualified class name of your ShowEpisode model
      }
    } else {
      // Handle the movie case
      $movie = Movie::inRandomOrder()->first();
      if ($movie) {
        $contentId = $movie->id;
        $contentType = 'App\Models\Movie'; // Use the fully qualified class name of your Movie model
      }
    }

//    $showEpisode = ShowEpisode::where('show_episode_status_id', 7)
//        ->whereHas('show', function ($query) {
//          $query->whereIn('show_status_id', [1, 2]);
//        })
//        ->inRandomOrder()
//        ->first();
//    $contentId = $showEpisode->id;

    // Ensure $startTime is appropriately set to the top of the hour
    // $startTime = $startTime->minute(0)->second(0);
    $modelClass = $contentType;

    return [
        'content_type'     => $contentType,
        'content_id'       => $contentId,
        'start_time'       => $startTime,
        'duration_minutes' => 60,
        'end_time'         => $startTime->copy()->addHour(), // End time is 1 hour later
        'type'             => $modelClass ? lcfirst(class_basename($modelClass)) : null, // Dynamically set the type
        'status'           => 'scheduled',
        'priority'         => $this->faker->numberBetween(0, 10),
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
