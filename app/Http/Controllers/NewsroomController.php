<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\NewsStory;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Nette\Utils\DateTime;

class NewsroomController extends Controller
{
    public function index()
    {
        return Inertia::render('Newsroom/Index', [
            'newsStories' => NewsStory::with('image', 'user', 'newsCategory', 'newsCategorySub', 'city', 'province', 'federalElectoralDistrict', 'subnationalElectoralDistrict', 'newsStatus', 'video')
                ->when(Request::input('search'), function ($query, $search) {
                  $lowerSearch = strtolower($search); // Convert search term to lowercase
                  $query->whereRaw('LOWER(title) like ?', "%{$lowerSearch}%")
                      ->orWhereRaw('LOWER(content_json) like ?', "%{$lowerSearch}%")
                      ->orWhereHas('user', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                      ->orWhereHas('newsCategory', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                      ->orWhereHas('newsCategorySub', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                      ->orWhereHas('city', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                      ->orWhereHas('province', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                      ->orWhereHas('federalElectoralDistrict', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                      ->orWhereHas('subnationalElectoralDistrict', function ($query) use ($lowerSearch) {
                        $query->whereRaw('LOWER(name) like ?', "%{$lowerSearch}%");
                      })
                        ->orWhere(function($query) use ($search) {
                          // Check if search query could be a year
                          if (preg_match('/^\d{4}$/', $search)) { // Regex to match a 4-digit year
                            $query->whereYear('published_at', $search);
                          }
                        })
                        ->orWhere(function($query) use ($search) {
                          // Check if search query could be a year-month
                          try {
                            $date = \Carbon\Carbon::createFromFormat('Y-m', $search);
                            $query->whereYear('published_at', $date->format('Y'))
                                ->whereMonth('published_at', $date->format('m'));
                          } catch (\Exception $e) {
                            // If it's not a valid year-month, do nothing
                          }
                        })
                        ->orWhere(function($query) use ($search) {
                          // Check if search query could be a date
                          try {
                            $date = \Carbon\Carbon::parse($search);
                            $query->whereDate('published_at', $date->format('Y-m-d'));
                          } catch (\Exception $e) {
                            // If it's not a valid date, do nothing
                          }
                        });
                })
                ->whereNotIn('status', [6, 7]) // don't return stories with a status of 6 published or 7 hidden
                ->latest()
                ->paginate(10, ['*'], 'news')
                ->withQueryString()
                ->through(fn($newsStory) => [
                    'id' => $newsStory->id,
                    'user' => [
                      'name' => $newsStory->user->name,
                    ],
                    'title' => $newsStory->title,
                    'slug' => $newsStory->slug,
                    'newsCategory' => $newsStory->newsCategory->name ?? null,
                    'newsCategorySub' => $newsStory->newsCategorySub->name ?? null,
                    'content' => $newsStory->content,
                    'city' => $newsStory->city->name ?? null,
                    'province' => $newsStory->province->name ?? null,
                    'federalElectoralDistrict' => $newsStory->federalElectoralDistrict->name ?? null,
                    'subnationalElectoralDistrict' => $newsStory->subnationalElectoralDistrict->name ?? null,
                    'image' => $newsStory->image,
                    'status' => $newsStory->newsStatus,
                    'video' => $newsStory->video ?? null,
                    'created_at' => $newsStory->created_at,
                    'published_at' => $newsStory->published_at ?? null,
                    'can' => [
                        'editNewsStory' => Auth::user()->can('update', NewsStory::class),
                        'deleteNewsStory' => Auth::user()->can('delete', NewsStory::class),
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'createNewsStory' => Auth::user()->can('create', NewsStory::class),
                'editNewsStory' => Auth::user()->can('update', NewsStory::class),
                'publishNewsStory' => Auth::user()->can('publish', NewsStory::class)
            ]
        ]);
    }

    public function publish(HttpRequest $request)
    {
        $newsStory = NewsStory::find($request->id);
        $newsStory->published_at = date('Y-m-d H:i:s');
        $newsStory->status = 6;
        $newsStory->update();

        return redirect()->route('newsroom')->with('message', 'News Story Published Successfully');
    }
}
