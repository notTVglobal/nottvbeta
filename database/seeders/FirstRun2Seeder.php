<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirstRun2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CreatorSeeder::class,
            ShowSeeder::class,
            ShowEpisodeSeeder::class,
            TeamSeeder::class,
            TeamMemberSeeder::class,
        ]);
    }
}
