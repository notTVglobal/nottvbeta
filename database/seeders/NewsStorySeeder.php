<?php

namespace Database\Seeders;

use App\Models\NewsCountry;
use App\Models\NewsStory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      // Specify the number of NewsStory instances you want to create
      $numberOfStories = 50; // for example, create 50 stories

      // Using the factory to create $numberOfStories instances of NewsStory
      NewsStory::factory($numberOfStories)->create();

    }
}
