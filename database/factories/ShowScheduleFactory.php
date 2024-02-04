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

    // Calculate the start time at the top of the current (or most recent) hour
    $startTime = Carbon::now()->subMinutes(Carbon::now()->minute)->addHours($hourIncrement);

    // Increment the static counter for the next item
    $hourIncrement++;

    // Determine whether this schedule is for a show or a movie
    $isShow = $this->faker->boolean;

    // Initialize fields
    $showId = null;
    $movieId = null;
    $showEpisodeId = null;

    if ($isShow) {
      $show = Show::inRandomOrder()->first();
      $showId = $show ? $show->id : null;
      if ($show) {
        $latestEpisode = ShowEpisode::where('show_id', $show->id)->orderBy('id', 'desc')->first();
        $showEpisodeId = $latestEpisode ? $latestEpisode->id + 1 : 1;
      }
    } else {
      $movie = Movie::inRandomOrder()->first();
      $movieId = $movie ? $movie->id : null;
    }

    return [
        'show_id' => $showId,
        'mist_stream_id' => MistStream::factory()->create()->id,
        'show_episode_id' => $showEpisodeId,
        'movie_id' => $movieId,
        'type' => $isShow ? 'show' : 'movie',
        'start_time' => $startTime,
        'end_time' => $startTime->copy()->addHour(), // End time is 1 hour later
        'status' => 'scheduled',
        'priority' => $this->faker->numberBetween(0, 10),
        'event_type' => $this->faker->randomElement(['one-time', 'recurring']),
    ];
  }
}
