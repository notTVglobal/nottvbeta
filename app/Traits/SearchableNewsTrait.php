<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

trait SearchableNewsTrait {
  /**
   * Apply search filters to a query based on the given search term and status filter.
   *
   * @param Builder $query
   * @param string|null $searchTerm
   * @param array $statusFilter
   * @return Builder
   */
  public function applySearch(Builder $query, ?string $searchTerm, array $statusFilter = []): Builder
  {
    // Check if a search term is provided
    if ($searchTerm) {
      // Convert search term to lowercase for case-insensitive searching
      $lowerSearch = strtolower($searchTerm);

      // Apply search filters to the query
      $query->where(function ($query) use ($lowerSearch, $searchTerm) {
        // Search within title
        $query->whereRaw('LOWER(title) like ?', "%{$lowerSearch}%")
            // Search within news categories
            ->orWhereHas('newsCategory', function ($query) use ($lowerSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
            })
            // Search within news category subcategories
            ->orWhereHas('newsCategorySub', function ($query) use ($lowerSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
            })
            // Search within city names
            ->orWhereHas('city', function ($query) use ($lowerSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
            })
            // Search within content JSON
            ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerSearch}%")
            // Check if the search query could be a year
            ->orWhere(function ($query) use ($searchTerm) {
              if (preg_match('/^\d{4}$/', $searchTerm)) { // Regex to match a 4-digit year
                $query->whereYear('published_at', $searchTerm);
              }
            })
            // Check if the search query could be a year-month
            ->orWhere(function ($query) use ($searchTerm) {
              try {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $searchTerm);
                $query->whereYear('published_at', $date->format('Y'))
                    ->whereMonth('published_at', $date->format('m'));
              } catch (\Exception $e) {
                // If it's not a valid year-month, do nothing
              }
            })
            // Check if the search query could be a date
            ->orWhere(function ($query) use ($searchTerm) {
              try {
                $date = \Carbon\Carbon::parse($searchTerm);
                $query->whereDate('published_at', $date->format('Y-m-d'));
              } catch (\Exception $e) {
                // If it's not a valid date, do nothing
              }
            });
      });
    }

    // Apply status filter if provided
    if (!empty($statusFilter)) {
      $query->whereIn('status', $statusFilter);
    }

    // Return the modified query
    return $query;
  }
}
