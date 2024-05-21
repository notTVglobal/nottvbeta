<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\NewsCountry;

class NewsCountriesTableSeeder extends Seeder
{
    public function run()
    {
        $batchSize = 500; // Define a suitable batch size
        $batchData = []; // Array to hold batch data

        try {
            // Read the JSON file
            $jsonPath = base_path('support_files/json_files/countries.json');
            if (File::exists($jsonPath)) {
                $countries = json_decode(File::get($jsonPath), true);

                foreach ($countries as $country) {
                    $batchData[] = [
                        'name' => $country['name']['common'] ?? null,
                        'iso_alpha2_code' => $country['cca2'] ?? null,
                        'iso_alpha3_code' => $country['cca3'] ?? null,
                        'population' => $country['population'] ?? null,
                        'capital' => $country['capital'][0] ?? null,
                        'area' => $country['area'] ?? null,
                        'region' => $country['region'] ?? null,
                        'subregion' => $country['subregion'] ?? null,
                        'official_language' => json_encode($country['languages'] ?? []),
                        'currency_code' => !empty($country['currencies']) ? array_key_first($country['currencies']) : null,
                        'flag_image_url' => $country['flags']['png'] ?? null,
                        'latitude' => $country['latlng'][0] ?? null,
                        'longitude' => $country['latlng'][1] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (count($batchData) >= $batchSize) {
                        NewsCountry::insert($batchData);
                        $batchData = []; // Reset the batch data
                    }
                }

                // Insert any remaining records
                if (!empty($batchData)) {
                    NewsCountry::insert($batchData);
                }

            } else {
                $this->handleJsonFileError();
            }
        } catch (\Exception $e) {
            $this->handleJsonFileError();
        }
    }

    private function handleJsonFileError()
    {
        // Handle the error, maybe use fallback data
        $fallbackCountries = [
            [
                'name' => 'Canada',
                'iso_alpha2_code' => 'CA',
                'iso_alpha3_code' => 'CAN',
                'population' => 37742154,
                'capital' => 'Ottawa',
                'area' => 9984670,
                'region' => 'Americas',
                'subregion' => 'Northern America',
                'official_language' => json_encode(['English', 'French']),
                'currency_code' => 'CAD',
                'flag_image_url' => 'https://restcountries.com/v3.1/flags/ca.png',
                'latitude' => 56.1304,
                'longitude' => -106.3468,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add other fallback countries as needed
        ];

        foreach ($fallbackCountries as $country) {
            NewsCountry::create($country);
        }
    }
}
