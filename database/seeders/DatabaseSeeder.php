<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
          // Admins, settings, initial categories
            FirstRunSeeder::class,

          // Testing data user data, shows, teams, etc.
            TestDataSeeder::class,

          // Testing schedule and schedule recurrence.
            ScheduleSeeder::class, // Seeds the schedule.
            ScheduleRecurrenceDetailsSeeder::class, // Seeds the schedule with recurring items.

        ]);
    }
}
