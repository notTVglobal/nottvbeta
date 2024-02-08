<?php

// database/seeders/PlaylistWithItemsSeeder.php

namespace Database\Seeders;

use App\Models\NewsStory;
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
    // Create several playlists
    ChannelPlaylist::factory(5)->create()->each(function ($playlist) {

      // For each playlist, attach movies
      Movie::factory(3)->create()->each(function ($movie) use ($playlist) {
        ChannelPlaylistItem::factory()->create([
            'playlist_id' => $playlist->id,
            'content_type' => 'App\Models\Movie',
            'content_id' => $movie->id,
        ]);
      });

//       Create movie trailers and attach them to the playlist
      MovieTrailer::factory(3)->create()->each(function ($movieTrailer) use ($playlist) {
        ChannelPlaylistItem::factory()->create([
            'playlist_id' => $playlist->id,
            'content_type' => 'App\Models\MovieTrailer',
            'content_id' => $movieTrailer->id,
        ]);
      });

      // Attach other content
      OtherContent::factory(12)->create()->each(function ($content) use ($playlist) {
        ChannelPlaylistItem::factory()->create([
            'playlist_id' => $playlist->id,
            'content_type' => 'App\Models\OtherContent',
            'content_id' => $content->id,
        ]);
      });

      // For each playlist, attach movies
      ShowEpisode::factory(9)->create()->each(function ($showEpisode) use ($playlist) {
        ChannelPlaylistItem::factory()->create([
            'playlist_id' => $playlist->id,
            'content_type' => 'App\Models\ShowEpisode',
            'content_id' => $showEpisode->id,
        ]);
      });

      // For each playlist, attach movies
      NewsStory::factory(6)->create()->each(function ($newsStory) use ($playlist) {
        ChannelPlaylistItem::factory()->create([
            'playlist_id' => $playlist->id,
            'content_type' => 'App\Models\NewsStory',
            'content_id' => $newsStory->id,
        ]);
      });

    });
  }
}
