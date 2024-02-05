<?php

namespace Database\Factories;

use App\Models\MistStream;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Show;
use App\Models\Movie;
use App\Models\ShowEpisode;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShowSchedule>
 */
class ShowScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition()
  {
  static $hourIncrement = 0; // Keep track of the hour increment for each item

    // Calculate the start time at the top of the current (or most recent) hour, then increment
    $startTime = Carbon::now()->startOfHour()->addHours($hourIncrement++);

    // Determine whether this schedule is for a show or a movie
    $isShow = $this->faker->boolean;
    $showId = null;
    $showEpisodeId = null;
    $movieId = null;
    $type = null;
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
        $showEpisodeId = $showEpisode->id;
        $showId = $showEpisode->show_id;
        $type = 'show';
      }
    } else {
      // Handle the movie case
      $movie = Movie::inRandomOrder()->first();
      if ($movie) {
        $movieId = $movie->id;
        $type = 'movie';
      }
    }

    // Ensure $startTime is appropriately set to the top of the hour
    // $startTime = $startTime->minute(0)->second(0);
    $mistStreamId = MistStream::inRandomOrder()->first()->id; // Assuming MistStream records exist


    return [
          'show_id' => $showId,
          'mist_stream_id' => $mistStreamId,
          'show_episode_id' => $showEpisodeId,
          'movie_id' => $movieId,
          'type' => $type,
          'start_time' => $startTime,
          'end_time' => $startTime->copy()->addHour(), // End time is 1 hour later
          'status' => 'scheduled',
          'priority' => $this->faker->numberBetween(0, 10),
          'event_type' => $this->faker->randomElement(['one-time', 'recurring']),
    ];
  }
}
