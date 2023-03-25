<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movie extends Model
{
//    protected $fillable = [
//        'name',
//        'description',
//        'file_path',
//        'file_url',
//    ];
    protected $guarded = [];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function movieCategory(): BelongsTo
    {
        return $this->belongsTo(MovieCategory::class)->withDefault([
            'name' => 'category',
            'description' => 'category description'
        ]);
    }

    public function movieCategorySub(): BelongsTo
    {
        return $this->belongsTo(MovieCategorySub::class)->withDefault([
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
}


