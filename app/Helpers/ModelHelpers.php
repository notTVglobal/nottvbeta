<?php

use App\Models\Show;
use App\Models\Movie;
use App\Models\MovieTrailer;
use App\Models\ShowEpisode;
use App\Models\NewsStory;
use App\Models\OtherContent;

if (!function_exists('getModelClass')) {
  function getModelClass($type): string {
    return match ($type) {
      'showEpisode' => ShowEpisode::class,
      'show' => Show::class,
      'movie' => Movie::class,
      'movieTrailer' => MovieTrailer::class,
      'newsStory' => NewsStory::class,
      'otherContent' => OtherContent::class,
      default => throw new \InvalidArgumentException("Invalid content type: $type"),
    };
  }
}
