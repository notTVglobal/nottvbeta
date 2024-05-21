<?php

namespace App\Console\Commands;

use App\Models\NewsCity;
use Illuminate\Console\Command;

class FixNewsCitySlugs extends Command
{
  protected $signature = 'fix:news-city-slugs';
  protected $description = 'Fix slugs for NewsCity by removing numeric suffix and appending province abbreviation for identical names';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $newsCities = NewsCity::with('province')->get();
    $slugCounts = [];

    foreach ($newsCities as $newsCity) {
      // Remove the '-xxx' suffix from the slug
      $originalSlug = $newsCity->slug;
      $baseSlug = preg_replace('/-\d+$/', '', $originalSlug);

      // Generate new slug with province abbreviation if necessary
      $newSlug = $baseSlug;
      if (!empty($slugCounts[$baseSlug])) {
        $slugCounts[$baseSlug]++;
        $newSlug = $baseSlug . '-' . $newsCity->province->abbreviation;
      } else {
        $slugCounts[$baseSlug] = 1;
      }

      $newsCity->slug = $newSlug;
      $newsCity->save();

      $this->info("Updated slug for city {$newsCity->name} from {$originalSlug} to {$newSlug}");
    }

    $this->info('All NewsCity slugs have been updated successfully.');
  }
}
