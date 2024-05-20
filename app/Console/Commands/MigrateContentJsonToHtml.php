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
      // Get all news stories with content_json not null and content column empty or null
      $newsStories = NewsStory::whereNotNull('content_json')
          ->where(function ($query) {
            $query->whereNull('content')
                ->orWhere('content', '=', '');
          })
          ->limit(10)  // Fetch a limited number of records for inspection
          ->get();

      $this->info('Found ' . $newsStories->count() . ' news stories to migrate.');

      foreach ($newsStories as $newsStory) {
        $this->info('ID: ' . $newsStory->id);
        $this->info('Content JSON: ' . json_encode($newsStory->content_json));
        $this->info('Content: ' . $newsStory->content);

        // Decode the JSON content if it's a string
        $jsonContent = is_string($newsStory->content_json) ? json_decode($newsStory->content_json, true) : $newsStory->content_json;

        if ($jsonContent) {
          // Convert JSON to HTML
          $htmlContent = $this->convertJsonToHtml($jsonContent);

          // Log the converted HTML content
          $this->info('HTML Content: ' . $htmlContent);

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
    $htmlContent = '';

    if (isset($jsonContent['content']) && is_array($jsonContent['content'])) {
      foreach ($jsonContent['content'] as $block) {
        $htmlContent .= $this->convertBlockToHtml($block);
      }
    }

    return $htmlContent;
  }

  /**
   * Convert a single block to HTML.
   *
   * @param array $block
   * @return string
   */
  private function convertBlockToHtml(array $block): string
  {
    $html = '';

    if (isset($block['type']) && isset($block['content'])) {
      switch ($block['type']) {
        case 'paragraph':
          $html .= '<p>' . $this->convertContentToHtml($block['content']) . '</p>';
          break;
        case 'heading':
          $level = isset($block['attrs']['level']) ? $block['attrs']['level'] : 1;
          $html .= '<h' . $level . '>' . $this->convertContentToHtml($block['content']) . '</h' . $level . '>';
          break;
        case 'bulletList':
          $html .= '<ul>' . $this->convertListItemsToHtml($block['content']) . '</ul>';
          break;
        case 'orderedList':
          $html .= '<ol>' . $this->convertListItemsToHtml($block['content']) . '</ol>';
          break;
        // Add more cases based on your JSON structure and needs
        default:
          $html .= '<div>' . $this->convertContentToHtml($block['content']) . '</div>';
          break;
      }
    }

    return $html;
  }

  /**
   * Convert list items to HTML.
   *
   * @param array $items
   * @return string
   */
  private function convertListItemsToHtml(array $items): string
  {
    $html = '';

    foreach ($items as $item) {
      if (isset($item['type']) && $item['type'] === 'listItem') {
        $html .= '<li>' . $this->convertBlockToHtml($item['content'][0]) . '</li>';
      }
    }

    return $html;
  }

  /**
   * Convert content array to HTML.
   *
   * @param array $content
   * @return string
   */
  private function convertContentToHtml(array $content): string
  {
    $html = '';

    foreach ($content as $node) {
      if (isset($node['type']) && $node['type'] === 'text') {
        $text = htmlspecialchars($node['text']);
        if (isset($node['marks'])) {
          foreach ($node['marks'] as $mark) {
            switch ($mark['type']) {
              case 'bold':
                $text = '<strong>' . $text . '</strong>';
                break;
              case 'italic':
                $text = '<em>' . $text . '</em>';
                break;
              case 'underline':
                $text = '<u>' . $text . '</u>';
                break;
              case 'link':
                $href = htmlspecialchars($mark['attrs']['href']);
                $rel = isset($mark['attrs']['rel']) ? htmlspecialchars($mark['attrs']['rel']) : '';
                $target = isset($mark['attrs']['target']) ? htmlspecialchars($mark['attrs']['target']) : '';
                $text = '<a href="' . $href . '" rel="' . $rel . '" target="' . $target . '">' . $text . '</a>';
                break;
              // Add more cases for different mark types if needed
            }
          }
        }
        $html .= $text;
      }
    }

    return $html;
  }
}