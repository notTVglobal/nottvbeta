<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Standard User TEST',
            'email' => 'user@not.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('nottv123'),
            'remember_token' => Str::random(10),
            'address1' => null,
            'address2' => null,
            'city' => null,
            'province' => null,
            'country' => null,
            'postalCode' => null,
            'phone' => null,
            'creatorNumber' => null,
            'subscriptionStatus' => null,
            'role_id' => 1,
            'isAdmin' => 0,
        ]);
        DB::table('users')->insert([
            'name' => 'Premium Subscriber TEST',
            'email' => 'subscriber@not.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('nottv123'),
            'remember_token' => Str::random(10),
            'address1' => null,
            'address2' => null,
            'city' => null,
            'province' => null,
            'country' => null,
            'postalCode' => null,
            'phone' => null,
            'creatorNumber' => null,
            'subscriptionStatus' => null,
            'role_id' => 2,
            'isAdmin' => 0,
        ]);
        DB::table('users')->insert([
            'name' => 'VIP TEST',
            'email' => 'vip@not.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('nottv123'),
            'remember_token' => Str::random(10),
            'address1' => null,
            'address2' => null,
            'city' => null,
            'province' => null,
            'country' => null,
            'postalCode' => null,
            'phone' => null,
            'creatorNumber' => null,
            'subscriptionStatus' => null,
            'role_id' => 3,
            'isAdmin' => 0,
        ]);
        $creatorTestId = DB::table('users')->insertGetId([
            'name' => 'Creator TEST',
            'email' => 'creator@not.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('nottv123'),
            'remember_token' => Str::random(10),
            'address1' => null,
            'address2' => null,
            'city' => null,
            'province' => null,
            'country' => null,
            'postalCode' => null,
            'phone' => null,
            'creatorNumber' => null,
            'subscriptionStatus' => null,
            'role_id' => 4,
            'isAdmin' => 0,
        ]);
        DB::table('creators')->insert([
            'user_id' => $creatorTestId,
        ]);
        
        \App\Models\User::factory(99)->create();
    }
}
