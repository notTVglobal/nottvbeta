<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChannelPlaylistItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChannelPlaylistItem>
 */
class ChannelPlaylistItemFactory extends Factory {
  protected $model = ChannelPlaylistItem::class;

  public function definition() {
    // Dynamically choose a content type
    $contentTypes = ['ShowEpisode', 'Movie', 'MovieTrailer', 'NewsStory', 'OtherContent'];
    $selectedType = $this->faker->randomElement($contentTypes);

    // Assuming you have a method to fetch or create a record for the chosen content type
    $content = $this->getContentRecord($selectedType);

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
    // Example logic to fetch or create a content record
    switch ($type) {
      case 'ShowEpisode':
        return \App\Models\ShowEpisode::factory()->create();
      case 'Movie':
        return \App\Models\Movie::factory()->create();
      case 'MovieTrailer':
        return \App\Models\MovieTrailer::factory()->create();
      case 'NewsStory':
        return \App\Models\NewsStory::factory()->create();
      case 'OtherContent':
        return \App\Models\OtherContent::factory()->create();
      // Add cases for other types
      default:
        // Handle unexpected types if necessary
        return ['id' => null];
    }
  }
}
