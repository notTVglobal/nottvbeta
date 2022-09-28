<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shows')->insert([
            'name' => 'notTV News',
            'description' => 'The notTV flagship news show.',
            'user_id' => '1',
            'team_id' => '2',
        ]);
        \App\Models\Show::factory(99)->create();
    }
}
