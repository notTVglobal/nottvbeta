<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFederalRiding extends Model {
  use HasFactory;

  protected $fillable = [
      'name',
      'news_province_id',
      'population',
      'area',
      'representative',
      'geo_coordinates',
      'geo_shape_json',
      'historical_data',
      'economic_indicators',
      'date_founded',
      'year_updated',
      'ed_code',
  ];

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function cities() {
    return $this->hasMany(NewsCity::class);
  }
}
