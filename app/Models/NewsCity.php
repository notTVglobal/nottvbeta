<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCity extends Model
{
    use HasFactory;

  protected $fillable = [
      'name', // Name of the city
      'news_province_id', // Foreign key to NewsProvinces
      'population', // Population of the city
      'area', // Total area of the city in square kilometers
      'geo_coordinates', // Latitude and longitude of the city
      'economic_indicators', // Economic indicators of the city
      'cultural_significance', // Cultural significance of the city
      'city_mayor', // Current mayor of the city
      'tourism_attractions', // Key tourism attractions in the city
      'time_zone', // Time zone of the city
      'city_website', // Official website of the city
      'year_incorporated', // Year the city was incorporated
      'airport_code' // IATA airport code
  ];

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function postalCodes() {
    return $this->hasMany(NewPostalCode::class);
  }
}
