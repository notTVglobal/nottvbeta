<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCountry;
use GuzzleHttp\Client;

class NewsCountriesTableSeeder extends Seeder
{
  public function run()
  {
    $client = new Client();
    $batchSize = 500; // Define a suitable batch size
    $batchData = []; // Array to hold batch data

    try {
      $response = $client->request('GET', 'https://restcountries.com/v3.1/all');
      $countries = json_decode($response->getBody()->getContents(), true);

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
          // Add other fields as necessary
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

    } catch (\Exception $e) {
      // Handle exception
      echo 'Exception when calling REST Countries API: ', $e->getMessage(), PHP_EOL;
    }
  }
}
