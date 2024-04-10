<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFederalElectoralDistrict extends Model {
  use HasFactory;

  protected $fillable = [
      'name',
      'slug',
      'description',
      'province_id',
      'country_id',
      'population',
      'area',
      'representative',
      'latitude',
      'longitude',
      'geo_shape_json',
      'historical_data',
      'economic_indicators',
      'date_founded',
      'year_updated',
      'ed_code',
      'created_at',
      'updated_at'
  ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }

  public function country() {
    return $this->belongsTo(NewsCountry::class);
  }

  public function cities() {
    return $this->hasMany(NewsCity::class);
  }

  public function postalCodes() {
    return $this->hasMany(NewsPostalCode::class);
  }

  public function subnationalElectoralDistricts() {
    return $this->hasMany(NewsSubnationalElectoralDistrict::class);
  }
}
