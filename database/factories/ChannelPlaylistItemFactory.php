<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\MovieTrailer;
use App\Models\NewsStory;
use App\Models\OtherContent;
use App\Models\Show;
use App\Models\ShowEpisode;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChannelPlaylistItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChannelPlaylistItem>
 */
class ChannelPlaylistItemFactory extends Factory {

  protected $model = ChannelPlaylistItem::class;

  public function definition() {
    // Dynamically choose a content type
    $contentTypes = ['Show', 'ShowEpisode', 'Movie', 'MovieTrailer', 'NewsStory', 'OtherContent'];
    $selectedType = $this->faker->randomElement($contentTypes);

    // Assuming you have a method to fetch or create a record for the chosen content type
    $content = $this->getContentRecord($selectedType);

    // If no existing content record is found, return an empty array to skip creation
    if (!$content) {
      return [];
    }

    return [
        'playlist_id'             => \App\Models\ChannelPlaylist::factory(),
        'content_type'            => $selectedType,
        'content_id'              => $content['id'], // Use the ID of the fetched or created content record
        'order'                   => $this->faker->numberBetween(1, 100),
        'custom_playback_options' => '{}', // Example empty JSON
        'metadata'                => '{}', // Example empty JSON
    ];
  }

  private function getContentRecord($type) {
    // Fetch an existing content record or return null if none exist
    return match ($type) {
      'Show' => Show::inRandomOrder()->first(),
      'ShowEpisode' => ShowEpisode::inRandomOrder()->first(),
      'Movie' => Movie::inRandomOrder()->first(),
      'MovieTrailer' => MovieTrailer::inRandomOrder()->first(),
      'NewsStory' => NewsStory::inRandomOrder()->first(),
      'OtherContent' => OtherContent::inRandomOrder()->first(),
      default => null,
    };
  }
}
