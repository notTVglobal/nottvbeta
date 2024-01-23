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
      'representation',
      'geo_coordinates',
      'historical_data',
      'economic_indicators',
      'date_founded'
  ];

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }
}
