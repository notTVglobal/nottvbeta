<?php

use App\Models\MovieCategory;
use App\Models\ShowCategory;
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
      Schema::table('movie_categories', function (Blueprint $table) {
        $table->string('slug')->after('name')->nullable();
      });

      Schema::table('show_categories', function (Blueprint $table) {
        $table->string('slug')->after('name')->nullable();
      });

      // Generate slugs for existing records in movie_categories
      $this->generateSlugs(MovieCategory::class);

      // Generate slugs for existing records in show_categories
      $this->generateSlugs(ShowCategory::class);

      // Alter table to add unique constraint
      Schema::table('movie_categories', function (Blueprint $table) {
        $table->string('slug')->unique()->change();
      });

      Schema::table('show_categories', function (Blueprint $table) {
        $table->string('slug')->unique()->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('movie_categories', function (Blueprint $table) {
        $table->dropUnique(['slug']);
        $table->dropColumn('slug');
      });

      Schema::table('show_categories', function (Blueprint $table) {
        $table->dropUnique(['slug']);
        $table->dropColumn('slug');
      });
    }

  /**
   * Generate slugs for the given model.
   *
   * @param string $modelClass
   * @return void
   */
  private function generateSlugs(string $modelClass)
  {
    $records = $modelClass::all();

    foreach ($records as $record) {
      $slug = Str::slug($record->name);

      // Ensure the slug is unique
      $originalSlug = $slug;
      $counter = 1;
      while ($modelClass::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter++;
      }

      $record->slug = $slug;
      $record->save();
    }
  }
};
