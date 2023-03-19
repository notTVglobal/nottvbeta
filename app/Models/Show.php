<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'category_id',
        'sub_category_id',
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
            'name' => 'no category',
            'description' => 'no category description'
        ]);
    }

    public function showSubCategory(): BelongsTo
    {
        return $this->belongsTo(ShowCategory::class)->withDefault([
            'name' => 'no sub category',
            'description' => 'no sub category description'
            ]);
    }

    public function showNotes()
    {
        return $this->hasMany(ShowNote::class);
    }

}

