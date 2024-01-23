<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $provinces = [
          [
              'name' => 'Ontario',
              'abbreviation' => 'ON',
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Quebec',
              'abbreviation' => 'QC',
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'New Brunswick',
              'abbreviation' => 'NB',
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Nova Scotia',
              'abbreviation' => 'NS',
              'year_joined_confederation' => 1867,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Manitoba',
              'abbreviation' => 'MB',
              'year_joined_confederation' => 1870,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Northwest Territories',
              'abbreviation' => 'NT',
              'year_joined_confederation' => 1870,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'British Columbia',
              'abbreviation' => 'BC',
              'year_joined_confederation' => 1871,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Prince Edward Island',
              'abbreviation' => 'PE',
              'year_joined_confederation' => 1873,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Yukon Territory',
              'abbreviation' => 'YT',
              'year_joined_confederation' => 1898,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Alberta',
              'abbreviation' => 'AB',
              'year_joined_confederation' => 1905,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Saskatchewan',
              'abbreviation' => 'SK',
              'year_joined_confederation' => 1905,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Newfoundland and Labrador',
              'abbreviation' => 'NL',
              'year_joined_confederation' => 1949,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],
          [
              'name' => 'Nunavut',
              'abbreviation' => 'NU',
              'year_joined_confederation' => 1999,
              'population' => null,
              'area' => null,
              'capital' => null,
              'time_zone' => null,
              'geo_coordinates' => null,
          ],

      ];

      foreach ($provinces as &$province) {
        $province['created_at'] = Carbon::now();
        $province['updated_at'] = Carbon::now();
      }

      DB::table('news_provinces')->insert($provinces);
    }
}
