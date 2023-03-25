<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Show extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [

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
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function showStatus()
    {
        return $this->belongsTo(ShowStatus::class);
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

    public function showCategory(): BelongsTo
    {
        return $this->belongsTo(ShowCategory::class)->withDefault([
            'name' => 'category',
            'description' => 'category description'
        ]);
    }

    public function showCategorySub(): BelongsTo
    {
        return $this->belongsTo(ShowCategorySub::class)->withDefault([
            'name' => 'sub category',
            'description' => 'sub category description'
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

}

