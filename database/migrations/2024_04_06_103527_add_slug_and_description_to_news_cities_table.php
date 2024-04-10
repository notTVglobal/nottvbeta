<?php

use App\Models\NewsCity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_cities', function (Blueprint $table) {
          $table->string('slug')->after('name')->nullable(); // Step 1: Add column without unique constraint
          $table->text('description')->after('slug')->nullable();
        });

      $cities = NewsCity::all();
      foreach ($cities as $city) {
        // Generate the initial slug from the city name
        $basicSlug = Str::slug($city->name);
        $slug = $basicSlug;

        // Check if the slug is already taken
        $existingSlugCount = NewsCity::where('slug', $slug)->count();

        if ($existingSlugCount > 0) {
          // If taken, try appending the province abbreviation to the slug
          $slugWithProvince = $basicSlug . '-' . Str::slug($city->province->abbreviation);

          // Check again to avoid conflicts with the new slug
          $existingSlugWithProvinceCount = NewsCity::where('slug', $slugWithProvince)->count();

          // If still taken, fallback to appending the city ID
          $slug = $existingSlugWithProvinceCount > 0 ? $basicSlug . '-' . $city->id : $slugWithProvince;
        }

        // Update the city with the unique slug
        $city->slug = $slug;
        $city->save();
      }

      Schema::table('news_cities', function (Blueprint $table) {
        $table->string('slug')->unique()->change(); // Step 3: Now add the unique constraint
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_cities', function (Blueprint $table) {
          $table->dropUnique(['slug']);
          $table->dropColumn('slug', 'description');
        });
    }
};
