<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Define the number of movies you want to create
      $numberOfMovies = 50;

      // Use the factory to create the movies
      Movie::factory()->count($numberOfMovies)->create();
    }
}
