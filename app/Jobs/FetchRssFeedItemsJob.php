<?php

namespace App\Jobs;

use App\Models\NewsRssFeedItemTemp;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\NewsRssFeed;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use function App\Http\Controllers\isHTML;
use function App\Http\Controllers\isXML;

class FetchRssFeedItemsJob implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected NewsRssFeed $newsRssFeed;
  protected int $imageLoopCount = 0;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(NewsRssFeed $newsRssFeed) {
    $this->newsRssFeed = $newsRssFeed;
  }


  private function isXML($data): bool {
    return preg_match('/<\?xml/', $data) === 1;
  }

  private function isHTML($data): bool {
    return preg_match('/<!DOCTYPE HTML|<html/i', $data) === 1;
  }

  /**
   * @throws \Exception
   */
  private function findImageUrl($item) {
    // Example patterns to check for image URLs
    $imagePatterns = [
        'image',        // <image>http://example.com/image.jpg</image>
        'enclosure',    // <enclosure url="http://example.com/image.jpg" type="image/jpeg"/>
        'media:content', // <media:content url="http://example.com/image.jpg" type="image/jpeg"/>
    ];

    $this->imageLoopCount ++;

    try {
//      if ($this->imageLoopCount < 2) {
//        Log::debug('Start imagePattern try');
//      }
      foreach ($imagePatterns as $pattern) {



        // Check if the pattern exists in the item
        if (isset($item->$pattern)) {
              $image = $item->$pattern;
//          if ($this->imageLoopCount < 2) {
//            Log::debug('Pattern is set: ', $image);
//          }
              // Depending on the pattern, the URL might be an attribute
              if ($pattern === 'enclosure' || $pattern === 'media:content') {
                $attributes = $image->attributes();
//                if ($this->imageLoopCount < 2) {
//                  Log::debug('First pattern: ', $attributes);
//                }
                if (isset($attributes['url'])) {
                  return (string) $attributes['url'];
                }
              }
              else
              {
//                if ($this->imageLoopCount < 2) {
//                  Log::debug('return image only, no pattern: ');
//                }
                return (string) $image;
              }
            }
//        if ($this->imageLoopCount < 2) {
//          Log::debug('outside the pattern loop, here\'s the item: ', ['item' => $item->asXML()]);
//        }

        // Convert the item to XML string
        $xmlString = $item->asXML();

        // Regular expression to find the first media:content with medium="image" and extract the URL
        $pattern = '/<media:content[^>]+medium="image"[^>]+url="([^"]+)"/';

        // Perform the regex match
        if (preg_match($pattern, $xmlString, $matches)) {
          // Check if a match was found and return the URL
          if (!empty($matches[1])) {
            return $matches[1];
          }
        }


          }

    } catch
      (\Exception $e) {

        Log::error('Fetch RSS Feed Items Job. Can\'t get images from feed items. RSS Feed: ' . $this->newsRssFeed->name . '.');

      }
    // Extract image URL from description if present
    if (isset($item->description)) {
      return $this->extractImageFromDescription($item->description);
    }
    return null; // Return null if no image URL is found
  }

  private function extractImageFromDescription($description) {
      $doc = new \DOMDocument();
      @$doc->loadHTML($description); // Suppress warnings from malformed HTML
      $imageTags = $doc->getElementsByTagName('img');

      if ($imageTags->length > 0) {
        $src = $imageTags->item(0)->getAttribute('src');

        return $src ?: null;
      }

      return null;
    }

  private function extractDescription($item) {
      $description = (string) $item->description;
      // Optionally, strip HTML tags if you want plain text
      $description = strip_tags($description);

      return $description;
    }

  private function fetchAndProcessRssFeed(NewsRssFeed $newsRssFeed) {
      $newItemCreated = false; // Flag to track if any new item is created
      $isFirstItem = true; // Flag to track if we are processing the first item

      try {
        // Fetch and process the RSS feed
        $feed = file_get_contents($newsRssFeed->url);
        if (!$this->isXML($feed) || $this->isHTML($feed)) {
          Log::error('XML parsing failed this is not a proper RSS Feed. Please check the URL. RSS Feed: ' . $this->newsRssFeed->name . '.');
          throw new \Exception("XML parsing failed this is not a proper RSS Feed. Please check the URL.");
        }

        // ... existing logic to check and parse the feed ...
        $xml = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);
//      collect($xml);
        $feedItems = json_decode(json_encode($xml->channel))->item; // Ensure this accesses the items correctly

        foreach ($xml->channel->item as $item) {
          $imageUrl = $this->findImageUrl($item); // Use the function to find the image URL
          $description = $this->extractDescription($item);
          // Create a unique identifier for the item, e.g., using its URL or title and date
          $uniqueIdentifier = $item->link; // Example, adjust based on your feed structure

          // Check if an item with this identifier already exists
          $existingItem = NewsRssFeedItemTemp::where('link', $uniqueIdentifier)->first();

          if (!$existingItem) {
            // Item doesn't exist, create a new record
            NewsRssFeedItemTemp::create([
                'news_rss_feed_id' => $this->newsRssFeed->id,
                'title'            => $item->title,
                'description'      => $description,
                'link'             => $item->link,
                'pubDate'          => isset($item->pubDate) ? Carbon::parse($item->pubDate)->toDateTimeString() : null, // Convert to a suitable format if necessary
                'image_url'        => $imageUrl,
                'extra_metadata'   => json_encode([
                  // Add any extra fields you want to store
//                  'additionalField1' => $item->additionalField1,
//                  'additionalField2' => $item->additionalField2,
                ]),
            ]);
            $newItemCreated = true; // Set flag to true as a new item is created
          } else {
            // Optionally update the existing item
            // $existingItem->update([...]);
          }
        }

        if ($newItemCreated) {
          // Update last_successful_update only if a new item was created
          $this->newsRssFeed->last_successful_update = now();
          $this->newsRssFeed->save();
        }

      } catch (\Exception $e) {
        // Handle any exceptions
        Log::error("Error processing RSS feed: " . $e->getMessage());
      } finally {
        // Update last_attempt regardless of success or failure
        $this->newsRssFeed->last_attempt = now();
        $this->newsRssFeed->save();
      }

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
//      Log::debug('Fetch RSS Feed for ' . $this->newsRssFeed->name . '.');
      $this->fetchAndProcessRssFeed($this->newsRssFeed);
    }
}
