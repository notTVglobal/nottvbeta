<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\NewsCity;
use App\Models\NewsPostalCode;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostalCodeSeeder extends Seeder
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
    $path = storage_path('app/csv/news_postal_codes.csv');

    // Set the path to save a list of cities missed during the postal code import,
    // this goes to our local storage: storage/app
    $dateTime = Carbon::now()->format('Y_m_d_His'); // Format as 'Year_Month_Day_HourMinuteSecond'
    $notFoundCitiesPath = 'txt/missing_cities_from_postal_code_import_'. $dateTime .'.txt';

    // Check if the CSV file exists
    if (!file_exists($path)) {
      // Log a message or output a line to the console to inform the user
      Log::alert('Postal codes CSV file does not exist. PostalCodeSeeder did not run.');

      return;
    }

    // Load the CSV document from a file path
    $csv = Reader::createFromPath($path, 'r');
    $csv->setHeaderOffset(0); // Set the CSV header offset

    // Load all NewsCity records into an associative array
    $cities = NewsCity::all()->pluck('id', 'name')->toArray();

    // Initialize variables for batch processing
    $batchSize = 500; // Determine a suitable batch size
    $insertData = [];
    $notFoundCities = [];

    // Get all the records
    foreach ($csv->getRecords() as $record) {
      $cityName = $record['City'];
      $postalCode = $record['PostalCode'];

      if (isset($cities[$cityName])) {
        $insertData[] = [
            'code'         => $postalCode,
            'news_city_id' => $cities[$cityName]
        ];

        // Insert in batches
        if (count($insertData) >= $batchSize) {
          $this->insertBatch($insertData);
//          NewsPostalCode::insert($insertData);
          $insertData = []; // Reset the array after batch insert
        }
      } else {
        $notFoundCities[$cityName] = true; // Store unique city names
      }
    }

      // Insert any remaining records
      if (!empty($insertData)) {
        NewsPostalCode::insert($insertData);
      }

    // Remove duplicate city names
    $notFoundCities = array_unique($notFoundCities);

    // Write the list of not found cities to a text file
    Storage::disk('local')->put($notFoundCitiesPath, implode("\n", $notFoundCities));

  }

  private function insertBatch($data) {
    $existingCodes = NewsPostalCode::whereIn('code', array_column($data, 'code'))->pluck('code')->toArray();
    $insertable = array_filter($data, function ($item) use ($existingCodes) {
      return !in_array($item['code'], $existingCodes);
    });

    if (!empty($insertable)) {
      NewsPostalCode::insert($insertable);
    }
  }
}
