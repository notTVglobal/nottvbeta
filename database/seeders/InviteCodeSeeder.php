<?php

namespace Database\Seeders;

use App\Models\InviteCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            ->count(20)
//            ->sequence(fn ($sequence) => ['code' => $sequence->index + 13])
            ->sequence(fn ($sequence) => ['code' => $sequence->index + 0 . Str::random(5)])
            ->create();
    }
}
