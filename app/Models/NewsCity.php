<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCity extends Model
{
    use HasFactory;

  protected $fillable = [
      'name', // Name of the city
      'slug',
      'description',
      'type', // City, Town, etc.
      'province_id', // Foreign key to NewsProvince
      'country_id', // Foreign key to NewsCountry
      'population', // Population of the city
      'area', // Total area of the city in square kilometers
      'latitude', // Latitude of the city
      'longitude', // Longitude of the city
      'economic_indicators', // Economic indicators of the city
      'cultural_significance', // Cultural significance of the city
      'city_mayor', // Current mayor of the city
      'tourism_attractions', // Key tourism attractions in the city
      'city_website', // Official website of the city
      'year_incorporated', // Year the city was incorporated
      'airport_code', // IATA airport code
      'time_zone', // Time zone of the city
      'gmt_offset',
      'gmt_offset_dst',
      'dst_observed'
  ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function country()
  {
    return $this->belongsTo(NewsCountry::class, 'country_id');
  }

  public function federalElectoralDistrict() {
    return $this->belongsTo(NewsFederalElectoralDistrict::class);
  }

  public function postalCodes() {
    return $this->hasMany(NewPostalCode::class);
  }
}
