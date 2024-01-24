<?php

namespace Database\Seeders;

use App\Models\NewsCountry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Get the country ID from the iso_alpha3_code
      $countryISO3 = 'CAN';
      $countryId = NewsCountry::where('iso_alpha3_code', $countryISO3)->first()->id;

    }
}
