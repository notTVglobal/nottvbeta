<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsFederalElectoralDistrict;
use App\Models\NewsProvince;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NewsFederalElectoralDistrictsMoreInfoCANSeeder extends Seeder
{

  // This seeder parses data from Opendatasoft
  // https://data.opendatasoft.com/explore/dataset/georef-canada-federal-electoral-district%40public/export/?disjunctive.prov_name_en&disjunctive.fed_name_en
  // We prepare the csv file with the columns:
      // ed_code
      // name
      // population

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
      $path = storage_path('../support_files/csv_files/news_federal_electoral_districts_more_info_CAN.csv');

      // Check if the CSV file exists
      if (!file_exists($path)) {
        // Log a message or output a line to the console to inform the user
        Log::alert('news_federal_electoral_districts_more_info_CAN.csv file does not exist. NewsFederalElectoralDistrictsAddMoreInfoCANSeeder did not run.');        return;
      }

      // Load the CSV document from a file path
      $csv = Reader::createFromPath($path, 'r');
      $csv->setHeaderOffset(0); // Set the CSV header offset

      $batchSize = 500; // Define batch size
      $batchData = []; // Array to hold batch data



      foreach ($csv->getRecords() as $record) {
        $batchData[] = [
            'name' => $record['name'],
            'ed_code' => $record['ed_code'],
            'population' => $record['population'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Perform batch update when the batch size is reached
        if (count($batchData) >= $batchSize) {
          $this->batchUpdate($batchData);
          $batchData = [];
        }
      }

      // Update any remaining records
      if (!empty($batchData)) {
        $this->batchUpdate($batchData);
      }
    }

  private function batchUpdate(array $data)
  {
    $tableName = (new NewsFederalElectoralDistrict)->getTable();

    foreach ($data as $record) {

          // Find the corresponding record in the database by name
          // Update the found record with new data from the CSV
              try {
                DB::table($tableName)
                    ->where('name', $record['name'])
                    ->update([
                        'ed_code' => $record['ed_code'],
                        'population' => $record['population']
                    ]);
              } catch(\Exception $e) {
                // Handle cases where the federal riding is not found
                Log::warning("Federal riding not found for name: " . $record['name']);
              }
    }
  }

}
