<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowSchedule extends Model
{
    use HasFactory;

  protected $fillable = [
      'show_id',
      'mist_stream_id',
      'show_episode_id',
      'movie_id',
      'type', // discriminator: show, movie, trailer, live stream
      'start_time',
      'end_time',
      'status', // scheduled, live, completed, cancelled
      'priority', // defaults to 0
      'event_type' // one-time or recurring
  ];

  /**
   * Get the show associated with the schedule.
   */
  public function show()
  {
    return $this->belongsTo(Show::class, 'show_id');
  }

  /**
   * Get the mist stream associated with the schedule.
   */
  public function mistStream()
  {
    return $this->belongsTo(MistStream::class, 'mist_stream_id');
  }

  /**
   * Get the show episode associated with the schedule.
   */
  public function showEpisode()
  {
    return $this->belongsTo(ShowEpisode::class, 'show_episode_id');
  }

  /**
   * Get the movie associated with the schedule.
   */
  public function movie()
  {
    return $this->belongsTo(Movie::class, 'movie_id');
  }

  /**
   * Get the movie trailer associated with the schedule.
   */
  public function movieTrailer()
  {
    return $this->belongsTo(MovieTrailer::class, 'movie_trailer_id');
  }
}
