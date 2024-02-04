<?php

namespace App\Http\Controllers;
use App\Models\NewsRssFeedItemArchive;

use Illuminate\Http\Request;

class NewsRssFeedItemArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
      $archivedItems = NewsRssFeedItemArchive::all();
      return view('newsRssFeedItemsArchive.index', compact('archivedItems'));
      // Replace 'newsRssFeedItemsArchive.index' with your actual view name
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
      return view('newsRssFeedItemsArchive.create');
      // Replace 'newsRssFeedItemsArchive.create' with your actual view name
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
      $validatedData = $request->validate([
        // Validate your data here
      ]);

      NewsRssFeedItemArchive::create($validatedData);

      return redirect()->route('newsRssFeedItemsArchive.index')
          ->with('success', 'Archived item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  NewsRssFeedItemArchive $newsRssFeedItemArchive
     * @return \Illuminate\Http\Response
     */
    public function show(NewsRssFeedItemArchive $newsRssFeedItemArchive)
    {
      return view('newsRssFeedItemsArchive.show', compact('newsRssFeedItemArchive'));
      // Replace 'newsRssFeedItemsArchive.show' with your actual view name
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  NewsRssFeedItemArchive $newsRssFeedItemArchive
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(NewsRssFeedItemArchive $newsRssFeedItemArchive)
    {
      return view('newsRssFeedItemsArchive.edit', compact('newsRssFeedItemArchive'));
      // Replace 'newsRssFeedItemsArchive.edit' with your actual view name
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  NewsRssFeedItemArchive $newsRssFeedItemArchive
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, NewsRssFeedItemArchive $newsRssFeedItemArchive)
    {
      $validatedData = $request->validate([
        // Validate your data here
      ]);

      $newsRssFeedItemArchive->update($validatedData);

      return redirect()->route('newsRssFeedItemsArchive.index')
          ->with('success', 'Archived item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  NewsRssFeedItemArchive $newsRssFeedItemArchive
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(NewsRssFeedItemArchive $newsRssFeedItemArchive)
    {
      $newsRssFeedItemArchive->delete();

      return redirect()->route('newsRssFeedItemsArchive.index')
          ->with('success', 'Archived item deleted successfully.');
    }
}
