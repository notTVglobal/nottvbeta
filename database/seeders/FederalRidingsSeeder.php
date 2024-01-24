<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsFederalRiding;
use App\Models\NewsProvince;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FederalRidingsSeeder extends Seeder
{

  // This seeder parses data from Elections Canada
  // https://www.elections.ca/
  // We prepare the csv file with the columns:
      // riding_name_en
      // geo_point_2d
      // year
      //prov_name_en
      //geo_shape_json


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
      $path = storage_path('../storage/app/csv/news_federal_ridings.csv');

      // Check if the CSV file exists
      if (!file_exists($path)) {
        // Log a message or output a line to the console to inform the user
        Log::alert('news_federal_ridings.csv file does not exist. FederalRidingsSeeder did not run.');
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
          $provinceId = $provinces[$record['prov_name_en'] ?? null];

          if ($provinceId) {
            $insertData[] = [
                'name' => $record['riding_name_en'],
                'geo_coordinates' => $record['geo_point_2d'],
                'year_updated' => (int)$record['year'],
                'news_province_id' => $provinceId,
                'geo_shape_json' => $record['geo_shape_json'],
            ];

            if (count($insertData) >= $batchSize) {
              NewsFederalRiding::insert($insertData);
              $insertData = [];
            }
          }
        }

      if (!empty($insertData)) {
        NewsFederalRiding::insert($insertData);
      }

    }

}
