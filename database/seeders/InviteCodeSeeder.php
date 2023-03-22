<?php

namespace Database\Seeders;

use App\Models\InviteCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InviteCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\InviteCode::factory()
            ->count(100)
            ->sequence(fn ($sequence) => ['code' => $sequence->index + 13])
            ->create();
    }
}
