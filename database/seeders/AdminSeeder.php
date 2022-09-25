<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@not.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('nottv123'),
            'stripe_token' => Str::random(10),
            'remember_token' => Str::random(10),
            'address_1' => null,
            'address_2' => null,
            'city' => null,
            'province' => null,
            'country' => null,
            'postal_code' => null,
            'phone' => null,
            'creator_number' => null,
            'subscription_status' => null,
            'role_id' => 4,
            'isAdmin' => 1,
        ]);
    }
}
