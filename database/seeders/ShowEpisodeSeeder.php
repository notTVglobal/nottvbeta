<?php

namespace Database\Seeders;

use App\Models\Show;
use App\Models\ShowEpisode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowEpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
  {

    // Print a message to the console
    $this->command->info('Seeding ShowEpisodes. This process might take some time...');

    $shows = Show::all();

    foreach ($shows as $show) {
      ShowEpisode::factory(20)->create(['show_id' => $show->id]);
    }

    // Print a message indicating completion
    $this->command->info('ShowEpisodes seeding completed.');
  }
}
