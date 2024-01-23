<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsMlaRiding extends Model
{
    use HasFactory;

  protected $fillable = [
      'name', // Name of the MLA Riding
      'news_province_id', // Foreign key to NewsProvinces
      'population', // Population of the MLA Riding
      'area', // Total area of the MLA Riding in square kilometers
      'geo_coordinates',
      'economic_indicators',
      'historical_data',
      'date_founded'
  ];

  public function province() {
    return $this->belongsTo(NewsProvince::class);
  }
}
