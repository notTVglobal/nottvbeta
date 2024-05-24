<?php

// database/seeders/PlaylistWithItemsSeeder.php

namespace Database\Seeders;

use App\Models\NewsStory;
use App\Models\Show;
use App\Models\ShowEpisode;
use Illuminate\Database\Seeder;
use App\Models\ChannelPlaylist;
use App\Models\ChannelPlaylistItem;
use App\Models\Movie;
use App\Models\MovieTrailer;
use App\Models\OtherContent;

class PlaylistWithItemsSeeder extends Seeder
{
  public function run()
  {
    // Print a message to the console
//    $this->command->info('Seeding Playlists with existing items. This process might take some time...');

    // Fetch all existing records
    $shows = Show::all();
    $showEpisodes = ShowEpisode::all();
    $newsStories = NewsStory::all();
    $movies = Movie::all();
//    $otherContent = OtherContent::all();

    // Create several playlists
    ChannelPlaylist::factory(5)->create()->each(function ($playlist) use ($shows, $showEpisodes, $newsStories, $movies) {

      // Attach existing shows to the playlist if available
      if ($shows->isNotEmpty()) {
        $shows->random(min(9, $shows->count()))->each(function ($show) use ($playlist) {
          ChannelPlaylistItem::factory()->create([
              'playlist_id' => $playlist->id,
              'content_type' => 'App\Models\Show',
              'content_id' => $show->id,
          ]);
        });
      }

      // Attach existing show episodes to the playlist if available
      if ($showEpisodes->isNotEmpty()) {
        $showEpisodes->random(min(9, $showEpisodes->count()))->each(function ($showEpisode) use ($playlist) {
          ChannelPlaylistItem::factory()->create([
              'playlist_id' => $playlist->id,
              'content_type' => 'App\Models\ShowEpisode',
              'content_id' => $showEpisode->id,
          ]);
        });
      }

      // Attach existing news stories to the playlist if available
      if ($newsStories->isNotEmpty()) {
        $newsStories->random(min(6, $newsStories->count()))->each(function ($newsStory) use ($playlist) {
          ChannelPlaylistItem::factory()->create([
              'playlist_id' => $playlist->id,
              'content_type' => 'App\Models\NewsStory',
              'content_id' => $newsStory->id,
          ]);
        });
      }

      // Attach existing movies to the playlist if available
      if ($movies->isNotEmpty()) {
        $movies->random(min(3, $movies->count()))->each(function ($movie) use ($playlist) {
          ChannelPlaylistItem::factory()->create([
              'playlist_id' => $playlist->id,
              'content_type' => 'App\Models\Movie',
              'content_id' => $movie->id,
          ]);
        });
      }

      // Create movie trailers and attach them to the playlist
      MovieTrailer::factory(3)->create()->each(function ($movieTrailer) use ($playlist) {
        ChannelPlaylistItem::factory()->create([
            'playlist_id' => $playlist->id,
            'content_type' => 'App\Models\MovieTrailer',
            'content_id' => $movieTrailer->id,
        ]);
      });

      // Attach other content
//      OtherContent::factory(12)->create()->each(function ($content) use ($playlist) {
//        ChannelPlaylistItem::factory()->create([
//            'playlist_id' => $playlist->id,
//            'content_type' => 'App\Models\OtherContent',
//            'content_id' => $content->id,
//        ]);
//      });

      // Print a message indicating completion
//      $this->command->info('Playlists with existing items seeding completed.');


    });
  }
}
