<?php

namespace Database\Seeders;

use App\Models\NewsCountry;
use Illuminate\Database\Seeder;
use App\Models\NewsCity;
use App\Models\NewsProvince;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class NewsCitiesAndTownsCANSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
  {
    // Load the CSV document from a file path
    // we need to upload the dataset manually.
    // Put the CSV in storage/app/csv
    $path = storage_path('../support_files/csv_files/news_cities_CAN.csv');

    // Get the country ID from the iso_alpha3_code
    $countryISO3 = 'CAN';
    $countryId = NewsCountry::where('iso_alpha3_code', $countryISO3)->first()->id;

    // Check if the CSV file exists
    if (!file_exists($path)) {
      // Log a message or output a line to the console to inform the user
      Log::alert('news_cities_CAN.csv file does not exist. CitiesAndTownsSeeder did not run.');
      return;
    }

    // Load the CSV document from a file path
    $csv = Reader::createFromPath($path, 'r');
    $csv->setHeaderOffset(0); // Set the CSV header offset

    // Load all NewsProvince records into an associative array
    $provinces = NewsProvince::all()->pluck('id', 'name')->toArray();

    $batchSize = 500; // Define a suitable batch size
    $batchData = []; // Array to hold batch data

    // Get all the records
    foreach ($csv->getRecords() as $record) {
      if (in_array($record['Generic Term'], ['City', 'Town'])) {
        $provinceName = $record['Province - Territory'];
        $provinceId = $provinces[$provinceName] ?? null;

        if ($provinceId) {
          $batchData[] = [
              'name' => $record['Geographical Name'],
              'type' => $record['Generic Term'],
              'latitude' => $record['Latitude'], // Storing latitude separately
              'longitude' => $record['Longitude'], // Storing longitude separately
              'province_id' => $provinceId,
              'country_id' => $countryId,
              'created_at' => now(),
              'updated_at' => now(),
          ];

          if (count($batchData) >= $batchSize) {
            foreach ($batchData as $data) {
              NewsCity::create($data);
            }
            $batchData = [];
          }
        }
      }
    }

    // Insert any remaining data
    if (!empty($batchData)) {
      foreach ($batchData as $data) {
        NewsCity::create($data);
      }
    }
  }
}
