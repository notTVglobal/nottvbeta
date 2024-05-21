<?php

namespace App\Console\Commands;

use App\Models\NewsCategory;
use Illuminate\Console\Command;

class FixNewsCategorySlugs extends Command
{
  protected $signature = 'fix:news-category-slugs';
  protected $description = 'Fix slugs for NewsCategory by removing numeric suffix';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $categories = NewsCategory::all();

    foreach ($categories as $category) {
      // Remove the '-xxx' suffix from the slug
      $originalSlug = $category->slug;
      $newSlug = preg_replace('/-\d+$/', '', $originalSlug);

      $category->slug = $newSlug;
      $category->save();

      $this->info("Updated slug for category {$category->name} from {$originalSlug} to {$newSlug}");
    }

    $this->info('All NewsCategory slugs have been updated successfully.');
  }
}
