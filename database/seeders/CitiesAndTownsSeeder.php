<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCity;
use App\Models\NewsProvince;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class CitiesAndTownsSeeder extends Seeder
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
    $path = storage_path('../storage/app/csv/news_cities.csv');

    // Check if the CSV file exists
    if (!file_exists($path)) {
      // Log a message or output a line to the console to inform the user
      Log::alert('News cities CSV file does not exist. CitiesAndTownsSeeder did not run.');
      return;
    }

    // Load the CSV document from a file path
    $csv = Reader::createFromPath($path, 'r');
    $csv->setHeaderOffset(0); // Set the CSV header offset

    // Load all NewsProvince records into an associative array
    $provinces = NewsProvince::all()->pluck('id', 'name')->toArray();

    $batchSize = 500; // Define a suitable batch size
    $insertData = [];


    // Get all the records
    foreach ($csv->getRecords() as $record) {
      if (in_array($record['Generic Term'], ['City', 'Town'])) {
        $provinceName = $record['Province - Territory'];
        $provinceId = $provinces[$provinceName] ?? null;

        if ($provinceId) {
          $insertData[] = [
              'name' => $record['Geographical Name'],
              'type' => $record['Generic Term'],
              'geo_coordinates' => $record['Latitude'] . ',' . $record['Longitude'],
              'news_province_id' => $provinceId,
          ];

          if (count($insertData) >= $batchSize) {
            NewsCity::insert($insertData);
            $insertData = [];
          }
        }
      }
    }

    if (!empty($insertData)) {
      NewsCity::insert($insertData);
    }

  }
}
