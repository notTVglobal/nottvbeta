<?php

namespace App\Console\Commands;

use App\Models\NewsFederalElectoralDistrict;
use Illuminate\Console\Command;

class FixNewsFederalElectoralDistrictSlugs extends Command
{
  protected $signature = 'fix:news-federal-electoral-district-slugs';
  protected $description = 'Fix slugs for NewsFederalElectoralDistrict by removing numeric suffix';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $districts = NewsFederalElectoralDistrict::all();

    foreach ($districts as $district) {
      // Remove the '-xxx' suffix from the slug
      $originalSlug = $district->slug;
      $newSlug = preg_replace('/-\d+$/', '', $originalSlug);

      $district->slug = $newSlug;
      $district->save();

      $this->info("Updated slug for district {$district->name} from {$originalSlug} to {$newSlug}");
    }

    $this->info('All NewsFederalElectoralDistrict slugs have been updated successfully.');
  }
}
