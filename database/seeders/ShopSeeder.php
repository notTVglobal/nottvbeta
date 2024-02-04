<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Factories for products and categories
      Product::factory()->count(5)->create();
      ProductCategory::factory()->count(50)->create();

      // Product categories assignment
      $categories = ProductCategory::all();
      Product::all()->each(function ($product) use ($categories) {
        $product->categories()->attach(
            $categories->random(2)->pluck('id')->toArray()
        );
      });
    }
}
