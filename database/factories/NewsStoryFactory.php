<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\NewsCategory;
use App\Models\NewsCategorySub;
use App\Models\NewsCity;
use App\Models\NewsCountry;
use App\Models\NewsStory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsStoryFactory extends Factory
{
  protected $model = NewsStory::class;

  public function definition()
  {
    // get user Ids from users who are NewsMembers
    // change this once we have a NewsTeamMember seeder...
    $userIds = User::pluck('id')->all();
    // Randomly select a NewsCategory
    $newsCategory = NewsCategory::inRandomOrder()->first();
    // Select a NewsCategorySub that belongs to the chosen NewsCategory
    $newsCategorySub = NewsCategorySub::where('news_categories_id', $newsCategory->id)->inRandomOrder()->first();
    // randomly assign a city... then province from the city
    $newsCity = NewsCity::inRandomOrder()->first();
    // Generate a random status between 1 and 6
    $status = $this->faker->numberBetween(1, 6);
    // Get the country ID from the iso_alpha3_code
    $countryISO3 = 'CAN';
    $countryId = NewsCountry::where('iso_alpha3_code', $countryISO3)->first()->id;

    return [
        'user_id' => $this->faker->randomElement($userIds), // Assign manually or use a User factory if available
        'title' => $this->faker->sentence,
        'slug' => Str::slug($this->faker->sentence),
        'news_category_id' => $newsCategory->id, // Assign manually or use a NewsCategory factory
        'news_category_sub_id' => $newsCategorySub ? $newsCategorySub->id : null, // Assign manually or use a NewsCategorySub factory
      // ... Continue for other fields ...
        'content' => $this->faker->paragraph,
        'city_id' => $newsCity->id, // Assign manually or use a City factory
        'province_id' => $newsCity->province->id, // Assign manually or use a Province factory
        'country_id' => $countryId, // Assign manually or use a Country factory
        'news_federal_electoral_district_id' => null, // Assign manually
        'news_subnational_electoral_district_id' => null, // Assign manually
        'image_id' => function () { return Image::factory()->create()->id; }, // Assign manually or use an Image factory
        'status' => $status, // Assign manually or use a Status factory
        'published_at' => $status === 6 ? $this->faker->dateTime : null,
        'video_id' => null, // Assign manually or use a Video factory
        'content_json' => json_encode($this->faker->words(5)), // Example JSON content
    ];
  }
}
