<?php

namespace Database\Seeders;

use App\Models\NewsCountry;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsProvincesTableCANSeeder extends Seeder
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

      $provinces = [
          [
              'name' => 'British Columbia',
              'abbreviation' => 'BC',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1871,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Vancouver',
              'gmt_offset' => -8,
              'gmt_offset_dst' => -7,
              'dst_observed' => true,
          ],
          [
              'name' => 'Alberta',
              'abbreviation' => 'AB',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1905,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Edmonton',
              'gmt_offset' => -7,
              'gmt_offset_dst' => -6,
              'dst_observed' => true,
          ],
          [
              'name' => 'Saskatchewan',
              'abbreviation' => 'SK',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1905,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Regina',
              'gmt_offset' => -6,
              'gmt_offset_dst' => -6,
              'dst_observed' => false, // Note: Some areas do observe DST
          ],
          [
              'name' => 'Manitoba',
              'abbreviation' => 'MB',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1870,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Winnipeg',
              'gmt_offset' => -6,
              'gmt_offset_dst' => -5,
              'dst_observed' => true,
          ],
          [
              'name' => 'Ontario',
              'abbreviation' => 'ON',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Toronto', // Note: Ontario spans multiple time zones
              'gmt_offset' => -5,
              'gmt_offset_dst' => -4,
              'dst_observed' => true,
          ],
          [
              'name' => 'Quebec',
              'abbreviation' => 'QC',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Montreal', // Note: Quebec spans multiple time zones
              'gmt_offset' => -5,
              'gmt_offset_dst' => -4,
              'dst_observed' => true,
          ],
          [
              'name' => 'New Brunswick',
              'abbreviation' => 'NB',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Moncton',
              'gmt_offset' => -4,
              'gmt_offset_dst' => -3,
              'dst_observed' => true,
          ],
          [
              'name' => 'Nova Scotia',
              'abbreviation' => 'NS',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Halifax',
              'gmt_offset' => -4,
              'gmt_offset_dst' => -3,
              'dst_observed' => true,
          ],
          [
              'name' => 'Prince Edward Island',
              'abbreviation' => 'PE',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1873,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Halifax',
              'gmt_offset' => -4,
              'gmt_offset_dst' => -3,
              'dst_observed' => true,
          ],
          [
              'name' => 'Newfoundland and Labrador',
              'abbreviation' => 'NL',
              'type' => 'province',
              'country_id' => $countryId,
              'year_joined_confederation' => 1949,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/St_Johns',
              'gmt_offset' => -3.5,
              'gmt_offset_dst' => -2.5,
              'dst_observed' => true,
          ],
          [
              'name' => 'Yukon',
              'abbreviation' => 'YT',
              'type' => 'territory',
              'country_id' => $countryId,
              'year_joined_confederation' => 1898,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Whitehorse',
              'gmt_offset' => -7,
              'gmt_offset_dst' => -7, // As of 2020, Yukon observes MST year-round
              'dst_observed' => false,
          ],
          [
              'name' => 'Northwest Territories',
              'abbreviation' => 'NT',
              'type' => 'territory',
              'country_id' => $countryId,
              'year_joined_confederation' => 1870,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Yellowknife',
              'gmt_offset' => -7,
              'gmt_offset_dst' => -6,
              'dst_observed' => true,
          ],
          [
              'name' => 'Nunavut',
              'abbreviation' => 'NU',
              'type' => 'territory',
              'country_id' => $countryId,
              'year_joined_confederation' => 1999,
              'population' => null,
              'area' => null,
              'capital' => null,
              'latitude' => null,
              'longitude' => null,
              'time_zone' => 'America/Iqaluit', // Note: Nunavut spans multiple time zones
              'gmt_offset' => -5, // Eastern parts
              'gmt_offset_dst' => -4,
              'dst_observed' => true,
          ],

      ];

      foreach ($provinces as &$province) {
        $province['created_at'] = Carbon::now();
        $province['updated_at'] = Carbon::now();
      }

      DB::table('news_provinces')->insert($provinces);
    }
}
