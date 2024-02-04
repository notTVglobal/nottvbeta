<?php

namespace Database\Seeders;

use App\Models\ShowSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
  {
    // Create a ShowSchedule instance for each hour for the next 144 hours
    ShowSchedule::factory()->count(144)->create();
  }

}
