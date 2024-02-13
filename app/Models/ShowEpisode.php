<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Uid\Ulid;

class ShowEpisode extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ulid = (string) Ulid::generate();
        });
    }

//    protected $show;
//
//    public function __construct(array $attributes = [])
//    {
//        parent::__construct($attributes);
//        $this->show = new Show(); // Initialize the property with an instance of the Show model
//    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'mist_stream_wildcard_id',
        'name',
        'description',
        'user_id',
        'creative_commons_id',
        'show_id',
        'slug',
        'notes',
        'video_id',
        'video_url',
        'youtube_url',
        'video_embed_code',
        'isPublished',
        'isBeingEditedByUser_id',
        'image_id',
        'release_year',
        'release_dateTime',
    ];

    protected $with = ['show', 'image'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function showEpisodeStatus()
    {
        return $this->belongsTo(ShowEpisodeStatus::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function appSetting(): BelongsTo
    {
        return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
        ]);
    }

  public function creativeCommons()
  {
    return $this->belongsTo(CreativeCommons::class, 'creative_commons_id');
  }

  public function playlistItems()
  {
    return $this->morphMany(ChannelPlaylistItem::class, 'content');
  }

  public function mistStreamWildcard()
  {
    return $this->belongsTo(MistStreamWildcard::class, 'mist_stream_wildcard_id');
  }

//    public function showCategory(): HasOneThrough
//    {
//        return $this->hasOneThrough(Show::class, ShowCategory::class);
//    }
//
//    public function showCategorySub(): HasOneThrough
//    {
//        return $this->hasOneThrough(ShowCategorySub::class, Show::class);
//    }


    public function getRouteKeyName() {
        return 'slug';
    }

}
