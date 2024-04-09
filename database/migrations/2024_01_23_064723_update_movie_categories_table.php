<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateMovieCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Update the 'animation' category to 'animated'
      Schema::table('movie_categories', function (Blueprint $table) {
        DB::table('movie_categories')
            ->where('name', 'animated')
            ->update(['name' => 'Animated']);
      });

      // Insert new categories
      $newCategories = [
          ['name' => 'Adventure', 'description' => 'Focusing on exciting and often risky journeys or expeditions, typically in exotic or dangerous locations.'],
          ['name' => 'Family', 'description' => 'Movies that are suitable for all ages, often focusing on family-friendly themes and characters, and designed to be enjoyed by both children and adults.'],
          ['name' => 'Mystery', 'description' => 'Centering around the unraveling of a mystery, often involving a crime or a puzzling situation that characters aim to solve.'],
          ['name' => 'Indie', 'description' => 'Independent films that are produced outside the major film studio system, often characterized by their innovative storytelling and artistic vision.'],
          ['name' => 'Short Film', 'description' => 'Films that have a shorter duration than feature-length movies, often used to explore new ideas or filmmaking techniques in a concise format.'],
          ['name' => 'Foreign Language', 'description' => 'Films in languages other than the viewer\'s native language, highlighting diverse cultures and perspectives.'],
          ['name' => 'LGBTQ+', 'description' => 'Films that explore the lives, experiences, and narratives of LGBTQ+ individuals and communities.'],
          ['name' => 'Superhero', 'description' => 'Given the popularity and distinct nature of superhero films, they could form their own category.'],
          ['name' => 'Noir', 'description' => 'Known for its dark, cynical, and shadowy cinematography, often in crime dramas.'],
          ['name' => 'Silent Film', 'description' => 'Older films without synchronized sound, focusing on visuals and expressive acting.'],
          ['name' => 'Cult Films', 'description' => 'Movies that have acquired a highly devoted but specific group of fans, often characterized by their unconventional narrative or style.']
      ];

      $this->addTimestamps($newCategories);

      DB::table('movie_categories')->insert($newCategories);
    }

  private function addTimestamps(array &$items)
  {
    $timestamp = now();
    foreach ($items as &$item) {
      $item['created_at'] = $timestamp;
      $item['updated_at'] = $timestamp;
    }
  }


  /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
