<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class, // Seeds initial users into the database.
            CreatorSeeder::class, // Seeds creator profiles, used for user profiles with additional creator attributes and permissions.
            TeamSeeder::class, // Seeds teams, representing production companies, groups or collaborations.
            TeamMemberSeeder::class, // Seeds members of teams, linking users to specific teams.
            ShowSeeder::class, // Seeds data for shows.
            ShowEpisodeSeeder::class, // Seeds individual episodes for each show.
            ScheduleSeeder::class, // Seeds the schedule.
            ShopSeeder::class, // Seeds product categories and product data.
            NewsStorySeeder::class, // Seeds news stories.
            PlaylistWithItemsSeeder::class, // Seeds channel playlists with playlist items and movies, movie trailers, show episodes, other content and news stories.
        ]);
    }
}
