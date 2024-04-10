<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'creative_commons_id',
        'name',
        'extension',
        'size',
        'folder',
        'cloud_folder',
        'storage_location',
        'show_id',
        'show_episode_id',
        'team_id',
        'movie_id',
        'extra_metadata'
    ];

  protected $casts = [
      'extra_metadata' => 'array',
  ];

    public function show()
    {
        return $this->hasMany(Shows::class);
    }

    public function team()
    {
        return $this->hasMany(Teams::class);
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function movie()
    {
        return $this->hasMany(Movie::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function newsStory()
    {
        return $this->hasMany(NewsStory::class);
    }

    public function video()
    {
        return $this->hasMany(Video::class);
    }

    public function appSetting()
    {
        return $this->belongsTo(AppSetting::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function subscriptionPlan()
    {
        return $this->hasMany(SubscriptionPlan::class);
    }

//    public function appSetting(): BelongsTo
//    {
//        return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
//        ]);
//    }

  public function showImage(): \Illuminate\Database\Eloquent\Relations\HasManyThrough {
      return $this->hasManyThrough(Image::class, Show::class);
  }

  public function newsRssFeedItemArchives()
  {
    return $this->hasMany(NewsRssFeedItemArchive::class, 'image_id');
  }

  public function creativeCommons()
  {
    return $this->belongsTo(CreativeCommons::class, 'creative_commons_id');
  }

}
