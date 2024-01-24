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
      'year_joined_confederation',
      'population',
      'area',
      'geo_coordinates',
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

  public function federalRidings() {
    return $this->hasMany(NewsFederalRiding::class);
  }

  public function mlaRidings() {
    return $this->hasMany(NewsMlaRiding::class);
  }
}
