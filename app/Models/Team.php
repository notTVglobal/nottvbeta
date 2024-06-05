<?php

namespace App\Models;

use App\Http\Resources\ImageResource;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Team extends Model {
  use HasFactory;

  protected $fillable = [
      'user_id',
      'image_id',
      'name',
      'description',
      'public_message',
      'totalSpots',
      'team_members_profiles_are_visible',
      'slug',
      'isBeingEditedByUser_id',
      'team_leader',
      'team_status_id',
      'www_url',
      'instagram_name',
      'telegram_url',
      'twitter_handle',
  ];

  protected $casts = [
      'team_members_profiles_are_visible' => 'boolean',
  ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  protected $appends = ['nextBroadcast'];

  public function teamStatus(): BelongsTo {
    return $this->belongsTo(TeamStatus::class, 'team_status_id');
  }

  public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class);
  }

  public function movies(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Movie::class);
  }

  public function managers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
    return $this->belongsToMany(User::class, 'team_managers', 'team_id', 'user_id')
        ->using(TeamManager::class)
        ->as('teamManagers')
        ->withTimestamps();
//        ->addSelect('users.*', 'users.id as user_id', 'users.name', 'users.email', 'users.phone', 'users.profile_photo_path');
  }

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  // Define relationship with creators
  public function creators(): BelongsToMany {
    return $this->belongsToMany(Creator::class, 'team_members', 'team_id', 'user_id');
  }

  public function members(): BelongsToMany {
    return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
        ->using(TeamMember::class)
        ->as('teamMembers')
        ->withPivot('active', 'team_profile_is_public', 'created_at', 'updated_at')
        ->withTimestamps()
//        ->with('user') // Ensure the 'user' relationship is loaded
        ->select('id', 'name', 'email', 'phone', 'profile_photo_path');
  }

  // Define the relationship with members
//  public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
//    return $this->belongsToMany(User::class, 'team_members')
//        ->using(TeamMember::class)
//        ->withPivot('active', 'team_profile_is_public', 'created_at', 'updated_at')
//        ->withTimestamps()
//        ->select('id', 'name', 'email', 'phone', 'profile_photo_path', 'profile_photo_url');
//  }


  public function teamLeader(): BelongsTo {
    return $this->belongsTo(Creator::class, 'team_leader');
  }

//    public function members()
//    {
//        return $this->belongsToMany(User::class, 'team_members')->using(TeamMember::class)
//            ->as('teamMembers')
//            ->withPivot('active')
//            ->withTimestamps()
//        ->select('name', 'email');
//    }

  public function image(): BelongsTo {
    return $this->belongsTo(Image::class);
  }

//    public function appSetting()
//    {
//        return $this->belongsTo(AppSetting::class);
//    }

  public function appSetting(): BelongsTo {
    return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
    ]);
  }

  public function scheduleIndexes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(SchedulesIndex::class, 'team_id');
  }

  public function getNextBroadcastAttribute() {
//    Log::info('Fetching next broadcasts for team: ' . $this->id);

    $scheduleIndexes = $this->scheduleIndexes->load('content');
//    Log::info("Loaded scheduleIndexes count: " . $scheduleIndexes->count());

    if ($scheduleIndexes->isEmpty()) {
//      Log::info("No scheduleIndexes found for team: " . $this->id);
      return [];
    }

    return $scheduleIndexes->load('content.image.appSetting')->map(function ($scheduleIndex) {
      $contentType = get_class($scheduleIndex->content);
      $content_type = match ($contentType) {
        \App\Models\Show::class => 'show',
        \App\Models\Movie::class => 'movie',
        \App\Models\ShowEpisode::class => 'showEpisode',
        default => null,
      };

      if ($content_type === null) {
        return null; // Skip unrecognized content types
      }

      // Decode next_broadcast_details if it's not null
      $broadcastDetails = $scheduleIndex->next_broadcast_details ? json_decode($scheduleIndex->next_broadcast_details, true) : [];

      $data = [
          'scheduleIndexId'  => $scheduleIndex->id,
          'name'             => $scheduleIndex->content->name ?? "Unnamed",
          'slug'             => $scheduleIndex->content->slug ?? "No slug",
          'type'             => $content_type,
          'broadcastDate'    => $scheduleIndex->next_broadcast ?? "No broadcast date",
          'broadcastDetails' => $broadcastDetails,
          'image'            => $scheduleIndex->content->image ? (new ImageResource($scheduleIndex->content->image))->resolve() : null,
          'description'      => $scheduleIndex->content->description,
      ];

      if ($content_type === 'show' || $content_type === 'movie') {
        $data['category'] = $scheduleIndex->content->getCachedCategory() ? [
            'id'          => $scheduleIndex->content->getCachedCategory()->id,
            'name'        => $scheduleIndex->content->getCachedCategory()->name,
            'description' => $scheduleIndex->content->getCachedCategory()->description,
        ] : null;
        $data['subCategory'] = $scheduleIndex->content->getCachedSubCategory() ? [
            'id'          => $scheduleIndex->content->getCachedSubCategory()->id,
            'name'        => $scheduleIndex->content->getCachedSubCategory()->name,
            'description' => $scheduleIndex->content->getCachedSubCategory()->description,
        ] : null;
      }

      if ($content_type === 'showEpisode') {
        $scheduleIndex->content->loadMissing('show');
        if ($scheduleIndex->content->show) {
          $data['show'] = [
              'name'        => $scheduleIndex->content->show->name,
              'slug'        => $scheduleIndex->content->show->slug,
              'description' => $scheduleIndex->content->show->description,
              'image'       => $scheduleIndex->content->show->image ? (new ImageResource($scheduleIndex->content->show->image))->resolve() : null,
              'category'    => $scheduleIndex->content->show->getCachedCategory() ? [
                  'id'          => $scheduleIndex->content->show->getCachedCategory()->id,
                  'name'        => $scheduleIndex->content->show->getCachedCategory()->name,
                  'description' => $scheduleIndex->content->show->getCachedCategory()->description,
              ] : null,
              'subCategory' => $scheduleIndex->content->show->getCachedSubCategory() ? [
                  'id'          => $scheduleIndex->content->show->getCachedSubCategory()->id,
                  'name'        => $scheduleIndex->content->show->getCachedSubCategory()->name,
                  'description' => $scheduleIndex->content->show->getCachedSubCategory()->description,
              ] : null,
          ];
        }
      }

//      if (!empty($additionalData)) {
//        $data['show'] = $additionalData['show'];
////        Log::info("Appending additional data for schedule index {$scheduleIndex->id}");
//      }

      return $data;
    });
  }


//  /**
//   * Search function for handling various search types on the Team model.
//   * It allows dynamic search functionality by type, which can be extended with more types as needed.
//   *
//   * @param string $query The search query string.
//   * @param string $type The type of search to perform, defaults to SEARCH_EPISODES.
//   * @return Collection Returns a collection of search results, depending on the type.
//   */
//
//  // Constants to define search types. These constants provide an easy reference for specifying search types.
//  const SEARCH_SHOW_EPISODES = 'showEpisodes'; // Search type for finding episodes related to the team's shows.
//
//  public function search($query, $type = self::SEARCH_SHOW_EPISODES): \Illuminate\Database\Eloquent\Collection|Collection {
//    Log::debug('Team search initiated', ['team_id' => $this->id, 'query' => $query, 'type' => $type]);
//
//    return match ($type) {
//      self::SEARCH_SHOW_EPISODES => $this->searchEpisodes($query),
//      default => collect(),
//    };
//  }
//
//  /**
//   * Searches episodes associated with shows that belong to this team.
//   * It includes complex filters like title, description, and release date searches,
//   * as well as filtering by episode and show status.
//   *
//   * @param string $query The search criteria input by the user.
//   * @return LengthAwarePaginator Paginated search results.
//   */
//  public function searchEpisodes(string $query): LengthAwarePaginator {
//
//    Log::debug('Searching episodes for team', ['team_id' => $this->id, 'query' => $query]);
////     Execute a query on the ShowEpisode model
//    $results = ShowEpisode::query()
//        // Select relevant fields from the show_episodes table and the slug from the shows table.
//        ->select('show_episodes.id', 'show_episodes.name', 'show_episodes.description',
//            'show_episodes.release_dateTime', 'show_episodes.slug', 'shows.slug as show_slug')
//        ->join('shows', 'show_episodes.show_id', '=', 'shows.id') // Join with the shows table to access show-specific fields.
//        ->with([
//            'image.appSetting', // Eagerly load the image relationship and its related appSetting for each episode.
//        ])
//        ->where('shows.team_id', $this->id) // Filter to include only episodes from shows of this team.
//        ->whereIn('shows.show_status_id', [1, 2]) // Filter shows that are either new (1) or active (2).
//        ->where('show_episodes.show_episode_status_id', 7) // Include only episodes that are published.
//        ->where(function ($queryBuilder) use ($query) {
//          // Add filters for matching episode titles or descriptions
//          $queryBuilder->where('show_episodes.name', 'like', '%' . $query . '%')
//              ->orWhere('show_episodes.description', 'like', '%' . $query . '%')
//              ->orWhere(function ($dateQuery) use ($query) {
//                // Additional nested conditionals for date searching.
//                // Check different formats of the date string provided by the user.
//                if ($date = $this->tryCarbonParse($query, 'Y-m')) {
//                  $dateQuery->whereYear('show_episodes.release_dateTime', $date->year)
//                      ->whereMonth('show_episodes.release_dateTime', $date->month);
//                } elseif ($date = $this->tryCarbonParse($query)) {
//                  $dateQuery->whereDate('show_episodes.release_dateTime', $date->toDateString());
//                }
//              });
//        })
//        ->orderBy('show_episodes.release_dateTime', 'desc') // Order the results by release date in descending order.
//        ->paginate(10) // Paginate the results to show 10 episodes per page.
//        ->get() // Get the results without pagination
//        ->map(fn($showEpisode) => $this->transformShowEpisode($showEpisode));
//
//    Log::debug('Episodes found', ['count' => $results->count()]);
//    Log::debug('SQL Query', ['query' => DB::getQueryLog()]);
//
//    return $results->through(function ($showEpisode) {
//      return new ImageResource($showEpisode);
//    });
//
//    ->
//    through(function ($showEpisode) {
//
//      // Use ImageResource to format the output including the related image and its settings.
//      return new ImageResource($showEpisode);
//    });
//  }


}
