<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Show extends Model
{
    use SoftDeletes;
    use HasFactory;

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

    public function getRouteKeyName() {
        return 'slug';
    }

    // tec21: I want to use episodes to make the Eloquent call easier to read.
    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }
    // showEpisodes is required for showEpisode.show to display

    public function status()
    {
        return $this->belongsTo(ShowStatus::class, 'show_status_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showRunner()
    {
        return $this->belongsTo(Creator::class);
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

    public function showNotes()
    {
        return $this->hasMany(ShowNote::class);
    }

  public function mistStreamWildcard()
  {
    return $this->belongsTo(MistStreamWildcard::class, 'mist_stream_wildcard_id');
  }

}

