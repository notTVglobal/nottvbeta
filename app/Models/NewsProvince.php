<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsProvince extends Model
{
    use HasFactory;

  protected $fillable = [
      'name',
      'news_province_id',
      'population',
      'area',
      'representation',
      'geo_coordinates',
      'historical_data',
      'economic_indicators',
      'date_founded'
  ];


  public function cities() {
    return $this->hasMany(NewsCity::class);
  }

  public function federalRidings() {
    return $this->hasMany(NewsFederalRiding::class);
  }

  public function mlaRidings() {
    return $this->hasMany(NewsMlaRiding::class);
  }
}
