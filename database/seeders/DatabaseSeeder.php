<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            FirstRunSeeder::class,  // Admins, settings, initial categories            TeamSeeder::class,
            FirstRunTestingSeeder::class, // Additional user data, shows, teams, etc.
        ]);

        // Call the ShopSeeder
        $this->call([
            ShopSeeder::class,
        ]);
    }
}
