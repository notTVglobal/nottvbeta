<?php

namespace App\Models;

use App\Services\MistServerService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

  public function schedules(): \Illuminate\Database\Eloquent\Relations\MorphMany {
    return $this->morphMany(ShowSchedule::class, 'content');
  }


  public static function generateStreamKey($showId)
  {
    try {
      $show = self::where('id', $showId)->firstOrFail();

      // Check if the show has a ULID, if not generate one and update the show
      if (is_null($show->ulid)) {
        $show->ulid = strtolower(Ulid::generate()); // Ensure ULID is lowercase
        $show->save();
      }

      $ulid = $show->ulid;



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
          'comment' => 'Automatically created with new show.',
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

}

