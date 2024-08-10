<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;

class ChangelogService {
  protected string $changelogFile;

  public function __construct() {
    // Initialize the changelog file path in the constructor
    $this->changelogFile = resource_path('views/markdown/changelog.md');
    $this->markdown = new CommonMarkConverter([
        'html_input' => 'escape',
        'allow_unsafe_links' => false,
    ]);
  }

  public function getLatestChangelog() {
    try {
      // Check if cached
      $latestChangelog = Cache::get('latest_changelog');
      if ($latestChangelog) {
        return $latestChangelog;
      }

      // Parse the changelog file directly
      if (!File::exists($this->changelogFile)) {
        throw new \Exception('Changelog file not found.');
      }

      $content = File::get($this->changelogFile);
      $latestChangelog = $this->parseChangelog($content);

      // Store in cache for 24 hours
      Cache::put('latest_changelog', $latestChangelog, now()->addDay());

      return $latestChangelog;
    } catch (\Exception $e) {
      Log::error('Failed to get the latest changelog: ' . $e->getMessage());

      return [
          'version' => 'unknown',
          'content' => 'Could not retrieve changelog information.',
      ];
    }
  }

  protected function parseChangelog($content): array {
    $latestChangelog = [];
    preg_match('/## v([0-9.]+).*?\n(.*?)\n## v/s', $content, $matches);

    if (isset($matches[1], $matches[2])) {
      $latestChangelog['version'] = trim($matches[1]);
      $latestChangelog['content'] = $this->formatChangelog($matches[2]);
    }

    return $latestChangelog;
  }

  /**
   * @throws CommonMarkException
   */
  protected function formatChangelog($content)
  {
    // Convert Markdown to HTML
    $htmlContent = $this->markdown->convert($content)->getContent();

    // Remove unnecessary newlines
    $htmlContent = str_replace("\n", "", $htmlContent);

    // Format the date to be larger and more prominent
    $htmlContent = preg_replace(
        '/<p>(\w+ \d{1,2}, \d{4})<\/p>/',
        '<p style="font-size: 1.5em; font-weight: bold; margin-bottom: 20px;">$1</p>',
        $htmlContent
    );

    // Ensure all <li> elements are processed, including nested ones
    $htmlContent = preg_replace_callback('/<li>(.*?)<\/li>/', function ($matches) {
      $line = trim($matches[1]); // Trim any leading/trailing whitespace
      $emoji = '✨'; // Default emoji

      // Determine the correct emoji and formatting based on the content
      if (stripos($line, 'New Feature') !== false) {
        $emoji = '🚀';
        $formattedLine = preg_replace('/^New Feature:/i', '<strong>New Feature:</strong>', $line);
      } elseif (stripos($line, 'Added') !== false) {
        $emoji = '✅';
        $formattedLine = preg_replace('/^Added/i', '<strong>Added</strong>', $line);
      } elseif (stripos($line, 'Improved') !== false) {
        $emoji = '🔧';
        $formattedLine = preg_replace('/^Improved/i', '<strong>Improved</strong>', $line);
      } elseif (stripos($line, 'Fixed') !== false) {
        $emoji = '🐛';
        $formattedLine = preg_replace('/^Fixed/i', '<strong>Fixed</strong>', $line);
      } else {
        $formattedLine = "<strong>{$line}</strong>";
      }

      // Return the formatted <li> with the correct emoji and styles
      return "<li style='margin-bottom: 10px;'>{$emoji} {$formattedLine}</li>";
    }, $htmlContent);

    // Ensure consistent padding and styling for nested lists
    $htmlContent = preg_replace('/<ul>/', '<ul style="padding-left: 20px; list-style-type: disc;">', $htmlContent);

    // Return the processed HTML content
    return $htmlContent;
  }




}
