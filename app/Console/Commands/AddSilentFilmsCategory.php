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
              ['name' => 'Silent Comedy', 'description' => 'Features physical comedy and slapstick, relying on visual gags and expressions, exemplified by actors like Charlie Chaplin and Buster Keaton.'],
              ['name' => 'Silent Drama', 'description' => 'Dramatic films that convey emotional narratives through expressive acting, visuals, and title cards, often dealing with intense and serious themes.'],
              ['name' => 'Silent Romance', 'description' => 'Centers on romantic stories, showcasing emotional expressions and relationships without spoken dialogue.'],
              ['name' => 'Silent Adventure', 'description' => 'Adventure films of the era, often featuring epic journeys, exotic locations, and daring feats, told through visual storytelling.'],
              ['name' => 'Silent Horror', 'description' => 'Horror films relying on atmosphere, visual effects, and makeup, often creating a sense of dread and suspense without sound.'],
              ['name' => 'Silent Sci-Fi', 'description' => 'Science fiction films presenting futuristic or imaginative themes through creative visuals and innovative special effects.'],
              ['name' => 'Silent Fantasy', 'description' => 'Fantasy films that use imaginative storytelling and special effects to create magical and mythical worlds.'],
              ['name' => 'Silent Historical', 'description' => 'Historical films portraying significant events or periods, often with elaborate sets and costumes to recreate the past.'],
              ['name' => 'Silent War', 'description' => 'War films depicting military conflicts and battles, often focusing on visuals and action sequences to convey the story.'],
              ['name' => 'Silent Western', 'description' => 'Westerns from the silent film era, featuring cowboys, outlaws, and frontier life, told through action and visual storytelling.'],
              ['name' => 'Silent Crime', 'description' => 'Crime films focusing on criminals, detectives, and suspenseful plots, using visual cues and title cards to unfold the story.'],
              ['name' => 'Silent Mystery', 'description' => 'Mystery films that build suspense and intrigue through visual storytelling, often involving detectives and puzzling scenarios.'],
              ['name' => 'Silent Documentary', 'description' => 'Documentaries of the era, presenting real-life events, people, and places through silent footage and title descriptions.'],
              ['name' => 'Silent Experimental', 'description' => 'Experimental films exploring new techniques in storytelling, visuals, and special effects, pushing the boundaries of cinema.'],
              ['name' => 'Silent Biographical', 'description' => 'Biographical films depicting the lives of historical figures, using visual narratives to portray their stories.'],
              ['name' => 'Silent Art Film', 'description' => 'Artistic films focusing on visual composition, symbolism, and innovative filmmaking techniques.'],
              ['name' => 'Silent Melodrama', 'description' => 'Melodramatic films characterized by sensational storytelling, expressive acting, and dramatic plots.'],
              ['name' => 'Silent Serial', 'description' => 'Serial films consisting of short episodes, often featuring cliffhangers and adventurous plots.'],
              ['name' => 'Silent Comedy-Drama', 'description' => 'Films that blend comedic and dramatic elements, showcasing a range of emotional storytelling through visuals.'],
              ['name' => 'Silent Avant-Garde', 'description' => 'Avant-garde films that experiment with narrative structures, visual techniques, and unconventional storytelling.'],
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
