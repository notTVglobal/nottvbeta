<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;

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
        Image::create([
            'name' => 'logo_black_311.png',
            'extension' => 'png',
            'size' => '13866',
        ]);
        // notTV White logo
        Image::create([
            'name' => 'logo_white_512.png',
            'extension' => 'png',
            'size' => '26851',
        ]);
        // Ping
        Image::create([
            'name' => 'Ping.png',
            'extension' => 'png',
            'size' => '97422',
        ]);
        // Colorbars
        Image::create([
            'name' => 'EBU_Colorbars.svg.png',
            'extension' => 'png',
            'size' => '3310',
        ]);
    }
}
