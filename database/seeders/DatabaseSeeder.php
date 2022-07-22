<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(99)->create();

         \App\Models\User::factory()->create([
             'name' => 'Travis Cross',
             'email' => 'travis@not.tv',
             'password' => bcrypt('we67djcm')
         ]);
    }
}
