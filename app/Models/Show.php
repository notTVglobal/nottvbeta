<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Uid\Ulid;

class Show extends Model
{
    use SoftDeletes;
    use HasFactory;

  protected static function booted()
  {
    static::creating(function ($model) {
      $model->ulid = (string) Ulid::generate();
    });
  }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'mist_stream_wildcard_id',
        'user_id',
        'team_id',
        'image_id',
        'name',
        'description',
        'meta',
        'slug',
        'isBeingEditedByUser_id',
        'show_status_id',
        'show_runner',
        'www_url',
        'instagram_name',
        'telegram_url',
        'twitter_handle',
        'notes',
        'first_release_year',
        'last_release_year',
        'show_category_id',
        'show_category_sub_id',
        'episode_play_order',
    ];

  protected $casts = [
      'ulid' => 'string',
      'meta' => 'json',
  ];

    public function getRouteKeyName(): string {
        return 'slug';
    }

    // tec21: I want to use episodes to make the Eloquent call easier to read.
    public function showEpisodes(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(ShowEpisode::class);
    }
    // showEpisodes is required for showEpisode.show to display

    public function status(): BelongsTo {
        return $this->belongsTo(ShowStatus::class, 'show_status_id');
    }

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }

    public function image(): BelongsTo {
        return $this->belongsTo(Image::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function showRunner(): BelongsTo {
        return $this->belongsTo(Creator::class, 'show_runner');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ShowCategory::class, 'show_category_id')->withDefault([
            'name' => '',
            'description' => ''
        ]);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(ShowCategorySub::class, 'show_category_sub_id')->withDefault([
            'name' => '',
            'description' => ''
            ]);
    }

    public function appSetting(): BelongsTo
    {
        return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
        ]);
    }

    public function showNotes(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(ShowNote::class);
    }

  public function mistStreamWildcard(): BelongsTo {
    return $this->belongsTo(MistStreamWildcard::class, 'mist_stream_wildcard_id');
  }

  public function schedules(): MorphMany {
    return $this->morphMany(Schedule::class, 'content');
  }

  public function scheduleIndexes(): MorphMany {
    return $this->morphMany(SchedulesIndex::class, 'content');
  }

  public function recordings(): MorphMany {
    return $this->morphMany(Recording::class, 'model');
  }

  /**
   * Get the push destinations associated with the show's wildcard.
   *
   * @return Collection
   */
  public function getMistStreamPushDestinationsAttribute(): Collection {
    // If there's no associated wildcard, return an empty collection.
    if (!$this->mistStreamWildcard) {
      return collect([]);
    }

    // Otherwise, return the push destinations of the related wildcard.
    return $this->mistStreamWildcard->mistStreamPushDestination;
  }

  // tec21: this is new, I added this after lots of frustration.
  // Maybe the getMistStreamPushDestinationsAttribute() is still valuable?
  public function mistStreamPushDestinations(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(MistStreamPushDestination::class, 'show_id', 'show_id');
  }

  /**
   * @throws \Exception
   */
  public static function generateStreamKey($showId)
  {
    try {
      $show = self::where('id', $showId)->firstOrFail();

      // Check if the show has a ULID, if not generate one and update the show
      if (is_null($show->ulid)) {
        $show->ulid = strtolower(Ulid::generate()); // Ensure ULID is lowercase
        $show->save();
      }

      $ulid = strtolower($show->ulid);

      // Begin transaction
      DB::beginTransaction();

      // Assuming the logic to generate a stream key involves interacting with
      // related models like MistStream and MistStreamWildcard

      $mistStream = MistStream::firstOrCreate([
          'name' => 'show',
      ], [
          'comment' => 'Created for Show integration.',
          'mime_type' => 'application/vnd.apple.mpegurl',
      ]);

      $mistStreamWildcard = MistStreamWildcard::create([
          'name' => 'show+' . $ulid,
          'comment' => 'Automatically created with new show.', // We don't have a way to clarify if it was generated through the Generate Key button on the GoLive page because that is a one-off for DB:Seeders.
          'mime_type'      => 'application/x-mpegURL',
          'source' => 'push://',
          'mist_stream_id' => $mistStream->id,
      ]);

      $show->update(['mist_stream_wildcard_id' => $mistStreamWildcard->id]);

      DB::commit();

      return $mistStreamWildcard; // Return the generated wildcard or any relevant data

    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Failed to create show and associated entities: {$e->getMessage()}");

      // Rethrow the exception for the controller to handle
      throw new \Exception('Failed to generate the stream key.');
    }
  }

  // Retrieves the cached category or loads it if not cached
  public function getCachedCategory()
  {
    return Cache::rememberForever('show_category_' . $this->show_category_id, function () {
      return $this->category()->withDefault([
          'name' => '',
          'description' => ''
      ])->first();  // Assuming the relationship could return null, handle it with a default.
    });
  }

  // Retrieves the cached subcategory or loads it if not cached
  public function getCachedSubCategory()
  {
    return Cache::rememberForever('show_subcategory_' . $this->show_category_sub_id, function () {
      return $this->subCategory()->withDefault([
          'name' => '',
          'description' => ''
      ])->first();
    });
  }

}

