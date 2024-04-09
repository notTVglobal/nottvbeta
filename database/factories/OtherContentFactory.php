<?php

namespace Database\Factories;

use App\Models\ShowCategory;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OtherContent;
use App\Models\ShowCategorySub;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtherContent>
 */
class OtherContentFactory extends Factory
{
  protected $model = OtherContent::class;

  public function definition()
  {
    // Randomly select a category ID from the specified ones
    $categoryIds = [16, 17, 18, 19];
    $selectedCategoryId = $this->faker->randomElement($categoryIds);

    // Fetch the category based on the selected ID
    $category = ShowCategory::find($selectedCategoryId);

    // Fetch a random sub-category that belongs to the selected category
    $subCategory = ShowCategorySub::where('show_categories_id', $selectedCategoryId)->inRandomOrder()->first();

    $video = Video::inRandomOrder()->first();

    return [
        'title' => $this->faker->sentence,
        'slug' => Str::slug($this->faker->sentence),
        'description' => $this->faker->paragraph,
        'type' => $category ? $category->name : 'Default', // Use the category name as type
        'duration' => $this->faker->numberBetween(1, 120), // Example: duration in minutes
        'active' => $this->faker->boolean,
        'show_category_id' => $selectedCategoryId,
        'show_category_sub_id' => $subCategory ? $subCategory->id : null,
        'image_id' => \App\Models\Image::factory(),
        'video_id' => $video->id,
        'user_id' => \App\Models\User::factory(),
        'metadata' => '{}', // Assuming JSON format; adjust as necessary
    ];
  }
}
