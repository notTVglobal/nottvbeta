<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPostalCode extends Model
{
    use HasFactory;

  protected $fillable = [
      'code', // The postal code
      'news_city_id', // Foreign key to NewsCities
      'city_section', // Population of the postal code area
      'area_coverage', // Description of the area covered by the postal code
      'geo_coordinates', // Latitude and longitude of the postal code area
      'historical_data', // Historical data related to the postal code
      'demographic_information' // Demographic information of the area
  ];
  public function city() {
    return $this->belongsTo(NewsCity::class);
  }
}
