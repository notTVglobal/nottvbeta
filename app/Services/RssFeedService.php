<?php

namespace App\Services;

use App\Models\NewsRssFeed;
use App\Models\NewsRssFeedItemTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RssFeedService {

  public function fetchAndProcessRssFeed(NewsRssFeed $newsRssFeed): void {
    $newItemCreated = false; // Flag to track if any new item is created

    try {
      $feed = file_get_contents($newsRssFeed->url);

      if (!$this->isXML($feed) || $this->isHTML($feed)) {
        Log::error('XML parsing failed; this is not a proper RSS Feed. Please check the URL. RSS Feed: ' . $newsRssFeed->name . '.');
        throw new \Exception("XML parsing failed; this is not a proper RSS Feed. Please check the URL.");
      }

      $xml = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);

      foreach ($xml->channel->item as $item) {
        $imageUrl = $this->findImageUrl($item);
        $description = $this->extractDescription($item);
        $uniqueIdentifier = $item->link;

        $existingItem = NewsRssFeedItemTemp::where('link', $uniqueIdentifier)->first();

        if (!$existingItem) {
          NewsRssFeedItemTemp::create([
              'news_rss_feed_id' => $newsRssFeed->id,
              'title'            => $item->title,
              'description'      => $description,
              'link'             => $item->link,
              'pubDate'          => isset($item->pubDate) ? Carbon::parse($item->pubDate)->toDateTimeString() : null,
              'image_url'        => $imageUrl,
              'extra_metadata'   => json_encode([]),
          ]);
          $newItemCreated = true;
        }
      }

      if ($newItemCreated) {
        $newsRssFeed->last_successful_update = now();
        $newsRssFeed->save();
      }

    } catch (\Exception $e) {
      Log::error("Error processing RSS feed: " . $e->getMessage());
    } finally {
      $newsRssFeed->last_attempt = now();
      $newsRssFeed->save();
    }
  }

  private function isXML($data): bool {
    return preg_match('/<\?xml/', $data) === 1;
  }

  private function isHTML($data): bool {
    return preg_match('/<!DOCTYPE HTML|<html/i', $data) === 1;
  }

  private function findImageUrl($item) {
    $imagePatterns = ['image', 'enclosure', 'media:content'];

    foreach ($imagePatterns as $pattern) {
      if (isset($item->$pattern)) {
        $image = $item->$pattern;

        if ($pattern === 'enclosure' || $pattern === 'media:content') {
          $attributes = $image->attributes();
          if (isset($attributes['url'])) {
            return (string) $attributes['url'];
          }
        } else {
          return (string) $image;
        }
      }

      $xmlString = $item->asXML();
      $pattern = '/<media:content[^>]+medium="image"[^>]+url="([^"]+)"/';

      if (preg_match($pattern, $xmlString, $matches)) {
        if (!empty($matches[1])) {
          return $matches[1];
        }
      }
    }

    if (isset($item->description)) {
      return $this->extractImageFromDescription($item->description);
    }
    return null;
  }

  private function extractImageFromDescription($description) {
    $doc = new \DOMDocument();
    @$doc->loadHTML($description);
    $imageTags = $doc->getElementsByTagName('img');

    if ($imageTags->length > 0) {
      return $imageTags->item(0)->getAttribute('src') ?: null;
    }
    return null;
  }

  private function extractDescription($item) {
    $description = (string) $item->description;
    return strip_tags($description);
  }
}
