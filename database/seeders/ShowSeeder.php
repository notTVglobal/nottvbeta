<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Show;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Show::create([
//            'name' => 'notTV News',
//            'description' => 'The notTV flagship news show.',
//            'user_id' => '1',
//            'team_id' => '2',
//            'slug' => 'nottv-news',
//            'image_id' => '4'
//        ]);

      $teams = Team::all();

      foreach ($teams as $team) {
        Show::factory(5)->forTeam($team->id)->create();
      }
    }
}
