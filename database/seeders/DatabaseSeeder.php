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
//            TeamSeeder::class,
//            ShowSeeder::class,
//            ShowEpisodeSeeder::class,
//            CreatorSeeder::class,
//            TeamMemberSeeder::class,

        ]);

        Product::factory()->count(5)->create();
//        ProductCategory::factory()->count(50)->create();

        $categories = ProductCategory::all();
        Product::all()->each(function ($product) use ($categories) {
            $product->categories()->attach(
                $categories->random(2)->pluck('id')->toArray()
            );
        });

    }
}
