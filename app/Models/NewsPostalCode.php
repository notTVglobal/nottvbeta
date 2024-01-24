<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPostalCode extends Model
{
    use HasFactory;

  protected $fillable = [
      'code', // The postal code
      'city_id', // Foreign key to NewsCity
      'province_id', // Foreign key to NewsProvince
      'country_id', // Foreign key to NewsCountry
      'news_federal_electoral_district_id',  // Foreign key to NewsFederalElectoralDistrict
      'news_subnational_electoral_district_id',  // Foreign key to NewsSubnationalElectoralDistrict
      'city_section', // Population of the postal code area
      'area_coverage', // Description of the area covered by the postal code
      'latitude', // Latitude of the postal code area
      'longitude', // Longitude of the postal code area
      'demographic_information', // Demographic information of the area
      'historical_data', // Historical data related to the postal code
  ];

  public function city() {
    return $this->belongsTo(NewsCity::class);
  }

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function country() {
    return $this->belongsTo(NewsCountry::class);
  }

  public function federalElectoralDistrict() {
    return $this->belongsTo(NewsFederalElectoralDistrict::class);
  }

  public function subnationalElectoralDistrict() {
    return $this->belongsTo(NewsSubnationalElectoralDistrict::class);
  }

}
