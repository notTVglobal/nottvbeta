<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsStory extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [

    ];

    protected $casts = [
        'content_json' => 'array'
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class)->withDefault([
          'name' => 'category',
          'description' => 'category description'
      ]);
    }

  public function newsCategorySub()
  {
    return $this->belongsTo(NewsCategorySub::class)->withDefault([
        'name' => 'sub category',
        'description' => 'sub category description'
    ]);
  }

    public function status()
    {
        return $this->belongsTo(NewsStatus::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

  public function city()
  {
    return $this->belongsTo(NewsCity::class);
  }

  public function province()
  {
    return $this->belongsTo(NewsProvince::class);
  }

  public function federalElectoralDistrict()
  {
    return $this->belongsTo(NewsFederalElectoralDistrict::class);
  }

  public function subnationalElectoralDistrict()
  {
    return $this->belongsTo(NewsSubnationalElectoralDistrict::class);
  }

}
