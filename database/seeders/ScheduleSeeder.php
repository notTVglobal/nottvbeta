<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run()
  {
    // Create a Schedule instance for each hour for the next 144 hours
//    Schedule::factory()->count(144)->create();

    // Create a model instance with a start time of March 10, 2023, at 10:00 AM
//    Schedule::factory()->count(144)->withCustomStartDay(2023, 5, 7, 10)->create();

    Schedule::factory()
        ->count(144)
        ->withStartDate(2024, 5, 11)
        ->create();

  }

}
