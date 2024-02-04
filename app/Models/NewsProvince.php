<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsProvince extends Model
{
    use HasFactory;

  protected $fillable = [
      'name',
      'abbreviation',
      'type',
      'country_id',
      'year_joined_confederation',
      'population',
      'area',
      'latitude',
      'longitude',
      'capital',
      'province_premier',
      'provinces_website',
      'time_zone',
      'gmt_offset',
      'gmt_offset_dst',
      'dst_observed'
  ];


  public function cities() {
    return $this->hasMany(NewsCity::class);
  }

  public function country() {
    return $this->belongsTo(NewsCountry::class);
  }

  public function federalElectoralDistricts() {
    return $this->hasMany(NewsFederalElectoralDistrict::class);
  }

  public function subnationalElectoralDistricts() {
    return $this->hasMany(NewsSubnationalElectoralDistrict::class);
  }
}
