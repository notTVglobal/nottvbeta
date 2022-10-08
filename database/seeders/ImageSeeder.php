<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // notTV Black logo
        DB::table('images')->insert([
            'name' => 'logo_black_311.png',
            'extension' => 'png',
            'size' => '13866',
        ]);
        // notTV White logo
        DB::table('images')->insert([
            'name' => 'logo_white_512.png',
            'extension' => 'png',
            'size' => '26851',
        ]);
        // Ping
        DB::table('images')->insert([
            'name' => 'Ping.png',
            'extension' => 'png',
            'size' => '97422',
        ]);
        // Colorbars
        DB::table('images')->insert([
            'name' => 'EBU_Colorbars.svg.png',
            'extension' => 'png',
            'size' => '3310',
        ]);
    }
}
