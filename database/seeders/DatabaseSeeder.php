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
        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@not.tv',
            'password' => bcrypt('nottv123'),
            'role_id' => 5
        ]);

        $this->call([
            UserSeeder::class,
            ShowSeeder::class,
            TeamSeeder::class,
        ]);
    }
}
