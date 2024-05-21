<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use App\Models\NewsFederalElectoralDistrict;
use App\Models\NewsCountry;
use App\Models\NewsProvince;
use Illuminate\Support\Str;

class NewsFederalElectoralDistrictsCANSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);

        // Load the CSV document from a file path
        $path = storage_path('../support_files/csv_files/news_federal_electoral_districts_CAN.csv');
        $countryISO3 = 'CAN';
        $country = NewsCountry::where('iso_alpha3_code', $countryISO3)->first();

        if (!$country) {
            Log::alert("Country with ISO code {$countryISO3} does not exist. NewsFederalElectoralDistrictsCANSeeder did not run.");
            return;
        }

        $countryId = $country->id;
        $provinces = NewsProvince::where('country_id', $countryId)->pluck('id', 'name')->toArray();

        // Check if the CSV file exists
        if (!File::exists($path)) {
            Log::alert('news_federal_electoral_districts_CAN.csv file does not exist. NewsFederalElectoralDistrictsCANSeeder did not run.');
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // Set the CSV header offset

        // Get all the records
        foreach ($csv->getRecords() as $record) {
            $provinceNameFromCsv = $record['province_name'] ?? null;
            $provinceId = $provinces[$provinceNameFromCsv] ?? null;

            if (!$provinceId) {
                Log::warning("Province with name {$provinceNameFromCsv} not found. Skipping record.");
                continue;
            }

            // Parse latitude and longitude from the combined string
            $coordinates = explode(',', trim($record['latitude_longitude'], '"'));
            $latitude = $coordinates[0] ?? null;
            $longitude = $coordinates[1] ?? null;

            NewsFederalElectoralDistrict::create([
                'name'           => $record['name'],
                'slug'           => Str::slug($record['name']),
                'latitude'       => $latitude,
                'longitude'      => $longitude,
                'year_updated'   => (int) $record['year_updated'],
                'province_id'    => $provinceId,
                'country_id'     => $countryId,
                'geo_shape_json' => $record['coordinates'], // Storing the geo_shape_json
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
