<?php
namespace App\Traits;

use Illuminate\Support\Facades\Request;

trait SearchableNewsTrait {
  public function applySearch($query): void {
    $search = Request::input('search');
    if ($search) {
      $lowerSearch = strtolower($search); // Convert search term to lowercase
      $query->where(function ($query) use ($lowerSearch, $search) {
        $query->whereRaw('LOWER(title) like ?', "%{$lowerSearch}%")
            ->orWhereHas('newsCategory', function ($query) use ($lowerSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
            })
            ->orWhereHas('newsCategorySub', function ($query) use ($lowerSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
            })
            ->orWhereHas('city', function ($query) use ($lowerSearch) {
              $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
            })
            ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerSearch}%")
            ->orWhere(function ($query) use ($search) {
// Check if search query could be a year
              if (preg_match('/^\d{4}$/', $search)) { // Regex to match a 4-digit year
                $query->whereYear('published_at', $search);
              }
            })
            ->orWhere(function ($query) use ($search) {
// Check if search query could be a year-month
              try {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $search);
                $query->whereYear('published_at', $date->format('Y'))
                    ->whereMonth('published_at', $date->format('m'));
              } catch (\Exception $e) {
// If it's not a valid year-month, do nothing
              }
            })
            ->orWhere(function ($query) use ($search) {
// Check if search query could be a date
              try {
                $date = \Carbon\Carbon::parse($search);
                $query->whereDate('published_at', $date->format('Y-m-d'));
              } catch (\Exception $e) {
// If it's not a valid date, do nothing
              }
            });
      });
    }
  }
}
