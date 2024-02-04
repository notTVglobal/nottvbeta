<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSubnationalElectoralDistrict extends Model
{
    use HasFactory;

  protected $fillable = [
      'name', // Name of the Subnational Electoral District
      'province_id', // Foreign key to NewsProvince
      'country_id', // Foreign key to NewsCountry
      'federal_electoral_district_id', // Foreign key to Federal Electoral District
      'population', // Population of the Subnational Electoral District
      'area', // Total area of the Subnational Electoral District in square kilometers
      'latitude', // Latitude of the Subnational Electoral District
      'longitude', // Longitude of the Subnational Electoral District
      'historical_data',
      'economic_indicators',
      'date_founded'
  ];

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function country() {
    return $this->belongsTo(NewsCountry::class);
  }

  public function federalElectoralDistrict() {
    return $this->belongsTo(NewsFederalElectoralDistrict::class);
  }

  public function postalCodes() {
    return $this->hasMany(NewsPostalCode::class);
  }

}
