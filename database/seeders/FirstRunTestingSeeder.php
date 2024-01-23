<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirstRunTestingSeeder extends Seeder
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
            TeamSeeder::class,
            TeamMemberSeeder::class,
            ShowSeeder::class,
            ShowEpisodeSeeder::class,
        ]);
    }
}
