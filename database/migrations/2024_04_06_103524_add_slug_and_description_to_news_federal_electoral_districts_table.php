<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('news_federal_electoral_districts', function (Blueprint $table) {
      $table->string('slug')->after('name')->nullable(); // Step 1: Add column without unique constraint
      $table->text('description')->after('slug')->nullable();
    });

    $districts = \App\Models\NewsFederalElectoralDistrict::all();
    foreach ($districts as $district) {
      // Generate the initial slug from the district name
      $basicSlug = Str::slug($district->name);
      $slug = $basicSlug;

      // Check if the slug is already taken
      $existingSlugCount = \App\Models\NewsFederalElectoralDistrict::where('slug', $slug)->count();

      if ($existingSlugCount > 0) {
        // If taken, append the province name to the slug
        $slug = $basicSlug . '-' . Str::slug($district->province->abbreviation);
      }

      // Update the district with the unique slug
      $district->slug = $slug;
      $district->save();
    }

    Schema::table('news_federal_electoral_districts', function (Blueprint $table) {
      $table->string('slug')->unique()->change(); // Step 3: Now add the unique constraint
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('news_federal_electoral_districts', function (Blueprint $table) {
      $table->dropUnique(['slug']);
      $table->dropColumn('slug', 'description');
    });
  }
};
