<?php

namespace App\Console\Commands;

use App\Models\NewsStory;
use Illuminate\Console\Command;

class MigrateContentJsonToHtml extends Command
{
  protected $signature = 'migrate:content-json-to-html';
  protected $description = 'Migrate content from content_json column to content column in HTML format';

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $this->info('Starting migration...');

    try {
      // Get all news stories with content_json not null and content column empty
      $newsStories = NewsStory::whereNotNull('content_json')
          ->whereNull('content')
          ->get();

      $this->info('Found ' . $newsStories->count() . ' news stories to migrate.');

      foreach ($newsStories as $newsStory) {
        // Decode the JSON content
//        $this->info('content_json type: ' . gettype($newsStory->content_json));
//        $this->info('content_json value: ' . print_r($newsStory->content_json, true));

        // Decode the JSON content if it's a string
        $jsonContent = is_string($newsStory->content_json) ? json_decode($newsStory->content_json, true) : $newsStory->content_json;


        if ($jsonContent) {
          // Convert JSON to HTML
          $htmlContent = $this->convertJsonToHtml($jsonContent);

          // Update the content column
          $newsStory->content = $htmlContent;
          $newsStory->save();

          $this->info('Migrated content for news story ID: ' . $newsStory->id);
        } else {
          $this->error('Failed to decode JSON for news story ID: ' . $newsStory->id);
        }
      }

      $this->info('Migration completed.');
    } catch (\Exception $e) {
      $this->error('An error occurred: ' . $e->getMessage());
      $this->error($e->getTraceAsString());
    }

    return 0;
  }

  /**
   * Convert JSON content to HTML.
   *
   * @param array $jsonContent
   * @return string
   */
  private function convertJsonToHtml(array $jsonContent): string
  {
    // Convert the JSON content to HTML
    // This is a placeholder implementation. Adjust based on your JSON structure.
    $htmlContent = '';

    foreach ($jsonContent as $block) {
      if (isset($block['type']) && isset($block['data'])) {
        switch ($block['type']) {
          case 'paragraph':
            $htmlContent .= '<p>' . htmlspecialchars($block['data']['text']) . '</p>';
            break;
          case 'heading':
            $level = isset($block['data']['level']) ? $block['data']['level'] : 1;
            $htmlContent .= '<h' . $level . '>' . htmlspecialchars($block['data']['text']) . '</h' . $level . '>';
            break;
          // Add more cases based on your JSON structure and needs
          default:
            $htmlContent .= '<div>' . htmlspecialchars($block['data']['text']) . '</div>';
            break;
        }
      }
    }

    return $htmlContent;
  }
}
