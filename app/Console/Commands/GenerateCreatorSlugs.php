<?php

namespace App\Console\Commands;

use App\Models\Creator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCreatorSlugs extends Command {
  protected $signature = 'generate:creator-slugs';
  protected $description = 'Generate unique slugs for existing creators';

  public function handle()
  {
    $totalCreators = Creator::count();
    $this->info("Total creators to process: $totalCreators");

    $processed = 0;
    Creator::with('user')->chunk(100, function ($creators) use (&$processed, $totalCreators) {
      foreach ($creators as $creator) {
        $originalSlug = Str::slug($creator->user->name);
        $creator->slug = $this->generateUniqueSlug($originalSlug);
        $creator->save();

        $processed++;
        $this->info("Processed $processed / $totalCreators: Slug for '{$creator->user->name}' set to '{$creator->slug}'");
      }
    });

    $this->info('Slugs generated successfully for all creators.');
  }

  protected function generateUniqueSlug($slug)
  {
    $originalSlug = $slug;
    $counter = 1;

    while (Creator::where('slug', $slug)->exists()) {
      $slug = $originalSlug . '-' . $counter;
      $counter++;
    }

    return $slug;
  }
}
