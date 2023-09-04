<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Creator;
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
        User::create([
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
        User::create([
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
        User::create([
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
        $creatorTestId = User::create([
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
        Creator::create([
            'user_id' => $creatorTestId->id,
        ]);

        \App\Models\User::factory(99)->create();
    }
}
