<?php

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
        Schema::table('news_categories', function (Blueprint $table) {
          $table->string('slug')->after('name')->nullable(); // Step 1: Add column without unique constraint
        });

      $newsCategories = \App\Models\NewsCategory::all();
      foreach ($newsCategories as $category) {
        // Generate a unique slug for each category by appending the category ID
        $uniqueSlug = Str::slug($category->name) . '-' . $category->id;

        // Assign the unique slug to the category
        $category->slug = $uniqueSlug;

        // Save the updated category
        $category->save();
      }

      Schema::table('news_categories', function (Blueprint $table) {
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
        Schema::table('news_categories', function (Blueprint $table) {
          $table->dropUnique(['slug']);
          $table->dropColumn('slug');
        });
    }
};
