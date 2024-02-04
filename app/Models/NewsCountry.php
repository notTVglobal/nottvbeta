<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCountry extends Model
{
    use HasFactory;

  protected $fillable = [
      'iso_alpha2_code',
      'iso_alpha3_code',
      'name',
  ];

  public function cities()
  {
    return $this->hasMany(NewsCity::class, 'country_id');
  }

  public function provinces()
  {
    return $this->hasMany(NewsProvince::class, 'country_id');
  }

  public function postalCodes()
  {
    return $this->hasMany(NewsPostalCode::class, 'country_id');
  }

  public function appSettings()
  {
    return $this->hasMany(AppSetting::class, 'country_id');
  }

  public function federalElectoralDistricts()
  {
    return $this->hasMany(NewsFederalElectoralDistrict::class, 'country_id');
  }

  public function subnationalElectoralDistricts()
  {
    return $this->hasMany(NewsSubnationalElectoralDistrict::class, 'country_id');
  }

}
