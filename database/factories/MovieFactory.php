<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Movie;
use App\Models\MovieCategory;
use App\Models\MovieCategorySub;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
  protected $model = Movie::class;

  public function definition()
  {

    $movieCategory = MovieCategory::inRandomOrder()->first();
    // Select a MovieCategorySub that belongs to the chosen MovieCategory
    $movieCategorySub = MovieCategorySub::where('movie_categories_id', $movieCategory->id)->inRandomOrder()->first();



    return [
        'user_id' => \App\Models\User::factory(),
        'creative_commons_id' => \App\Models\CreativeCommons::inRandomOrder()->first()->id,
        'team_id' => \App\Models\Team::factory(),
        'image_id' => function () { return Image::factory()->create()->id; },
        'name' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'slug' => Str::slug($this->faker->sentence),
        'extension' => 'mp4', // Example
        'size' => $this->faker->numberBetween(1000, 10000), // Example size in KB
        'file_path' => $this->faker->filePath(),
        'file_url' => $this->faker->url,
        'release_year' => $this->faker->year,
        'www_url' => $this->faker->url,
        'instagram_name' => $this->faker->userName,
        'telegram_url' => $this->faker->url,
        'twitter_handle' => $this->faker->userName,
        'movie_category_id' => $movieCategory,
        'movie_category_sub_id' => $movieCategorySub ? $movieCategorySub->id : null,
        'app_setting_id' => 1,
        'isBeingEditedByUser_id' => null, // Assume null or set as needed
        'logline' => $this->faker->sentence,
        'video_id' => \App\Models\Video::inRandomOrder()->first()->id,
        'status_id' => 2,
        'copyrightYear' => $this->faker->year,
        'releaseDateTime' => $this->faker->dateTime,
    ];
  }
}
