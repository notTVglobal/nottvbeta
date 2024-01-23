<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShowCategorySubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Example sub-category groups with their respective category names
      $subCategoryGroups = [
          'Action' => [
              ['name' => 'SubCat Name 1', 'description' => 'SubCat Description 1'],
            // ... more subcategories for 'Action' category
          ],
          'Drama' => [
              ['name' => 'SubCat Name 2', 'description' => 'SubCat Description 2'],
            // ... more subcategories for 'Drama' category
          ],
        // ... other category groups
      ];

      foreach ($subCategoryGroups as $categoryName => $subCategories) {
        $categoryId = $this->getCategoryIdByName($categoryName);
        if ($categoryId) {
          $this->addCategoryId($subCategories, $categoryId);
          DB::table('show_category_subs')->insert($subCategories);
        }
      }
    }

  private function getCategoryIdByName($categoryName)
  {
    $category = DB::table('show_categories')->where('name', $categoryName)->first();
    return $category ? $category->id : null;
  }

  private function addCategoryId(&$subCategoryArray, $categoryId)
  {
    foreach ($subCategoryArray as &$subCategory) {
      $subCategory['show_categories_id'] = $categoryId;
    }
  }
}
