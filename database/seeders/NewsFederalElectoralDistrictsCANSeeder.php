<?php

namespace Database\Seeders;

use App\Models\NewsCountry;
use Illuminate\Database\Seeder;
use App\Models\NewsFederalElectoralDistrict;
use App\Models\NewsProvince;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NewsFederalElectoralDistrictsCANSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);

    // Load the CSV document from a file path
    $path = storage_path('../storage/app/csv/news_federal_electoral_districts_CAN.csv');
    $countryISO3 = 'CAN';
    $countryId = NewsCountry::where('iso_alpha3_code', $countryISO3)->first()->id;
    $provinces = NewsProvince::all()->pluck('id', 'name')->toArray();

    // Check if the CSV file exists
    if (!file_exists($path)) {
      Log::alert('news_federal_electoral_districts_CAN.csv file does not exist. NewsFederalElectoralDistrictsCANSeeder did not run.');
      return;
    }

    $csv = Reader::createFromPath($path, 'r');
    $csv->setHeaderOffset(0); // Set the CSV header offset

    // Get all the records
    foreach ($csv->getRecords() as $record) {
      $provinceNameFromCsv = $record['prov_name_en'] ?? null;
      $provinceId = $provinces[$provinceNameFromCsv] ?? null;

      NewsFederalElectoralDistrict::create([
          'name'           => $record['riding_name_en'],
          'latitude'       => $record['latitude'], // Storing latitude separately
          'longitude'      => $record['longitude'], // Storing longitude separately
          'year_updated'   => (int) $record['year'],
          'province_id'    => $provinceId,
          'country_id'     => $countryId,
          'geo_shape_json' => $record['geo_shape_json'],
          'created_at'     => now(),
          'updated_at'     => now(),
      ]);
    }
  }
}
