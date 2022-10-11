<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('episodes')->insert([
            'name' => 'Pilot',
            'description' => 'The very first episode.',
            'user_id' => '1',
            'team_id' => '2',
            'show_id' => '1',
            'slug' => 'pilot',
            'image_id' => '4',
            'notes' => json_encode(['1', 'first note']),
            'isPublished' => '0',
        ]);
        \App\Models\Episode::factory(99)->create();
    }
}
