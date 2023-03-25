<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Creator;
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
        $adminId = User::create([
            'name' => 'Administrator',
            'email' => 'admin@not.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('e?6EM^ym~^E8jcTBdx'),
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
            'isAdmin' => 1,
        ]);
        Creator::create([
            'user_id' => $adminId->id,
        ]);
    }
}
