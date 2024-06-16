<?php

namespace App\Console\Commands;

use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddSilentFilmsCategory extends Command
{
  protected $signature = 'movie:add-silent-films-category';
  protected $description = 'Add Silent Films category and subcategories to movie_category and movie_category_subs tables';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    DB::transaction(function () {
      $subCategoryGroups = [
          'Silent Films' => [
              ['name' => 'Silent Comedy', 'description' => 'Focuses on humorous and comedic narratives, often featuring physical comedy and slapstick.'],
              ['name' => 'Silent Drama', 'description' => 'Centers on serious and emotional narratives, exploring human conditions and experiences.'],
              ['name' => 'Silent Horror', 'description' => 'Aims to frighten and evoke fear through atmospheric storytelling and visuals.'],
              ['name' => 'Silent Adventure', 'description' => 'Focuses on exciting and adventurous narratives, often involving heroes, quests, and exploration.'],
              ['name' => 'Silent Romance', 'description' => 'Centers on romantic relationships and love stories, often with dramatic twists and turns.'],
              ['name' => 'Silent Science Fiction', 'description' => 'Explores futuristic and speculative concepts, often involving advanced technology and otherworldly scenarios.'],
              ['name' => 'Silent Fantasy', 'description' => 'Features magical and fantastical elements, often set in imaginary worlds.'],
              ['name' => 'Silent Historical', 'description' => 'Depicts historical events, figures, and periods through silent cinema techniques.'],
              ['name' => 'Silent Mystery', 'description' => 'Involves suspenseful and enigmatic plots, often with detectives or unsolved mysteries.'],
              ['name' => 'Silent Animation', 'description' => 'Utilizes animated techniques to tell stories without sound.'],
          ]
      ];
      $categoryDescription = 'Silent films are films with no synchronized recorded sound, especially with no spoken dialogue.';
      $categorySlug = 'silent-films';
      $categoryName = 'Silent Films';

      // Insert the category
      $category = MovieCategory::create([
          'name' => $categoryName,
          'slug' => $categorySlug,
          'description' => $categoryDescription,
      ]);

      // Insert the subcategories
      foreach ($subCategoryGroups['Silent Films'] as $subCategory) {
        MovieCategorySub::create([
            'movie_categories_id' => $category->id,
            'name' => $subCategory['name'],
            'description' => $subCategory['description'],
        ]);
      }
    });

    $this->info('Silent Films category and subcategories added successfully.');
  }
}
