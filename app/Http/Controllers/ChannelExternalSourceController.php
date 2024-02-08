<?php

namespace App\Http\Controllers;

use App\Models\ChannelExternalSource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChannelExternalSourceController extends Controller
{

  public function __construct() {

    $this->middleware('can:viewAny,' . ChannelExternalSource::class)->only(['index']);
    $this->middleware('can:view,channelExternalSource')->only(['show']);
    $this->middleware('can:create,' . ChannelExternalSource::class)->only(['create']);
    $this->middleware('can:store,' . ChannelExternalSource::class)->only(['store']);
    $this->middleware('can:edit,channelExternalSource')->only(['edit']);
    $this->middleware('can:update,channelExternalSource')->only(['update']);
    $this->middleware('can:delete,channelExternalSource')->only(['delete']);
    $this->middleware('can:restore,channelExternalSource')->only(['restore']);
    $this->middleware('can:forceDelete,channelExternalSource')->only(['forceDelete']);
  }

  public function adminSearchExternalSources(Request $request)
  {
    $search = $request->input('search', '');

    // Directly fetch mistStreams based on the search input.
    $externalSources = ChannelExternalSource::query()
        ->when($search, function ($query, $search) {
          // Use the $search variable captured from the request
          return $query->where('name', 'like', "%{$search}%");
        })
        ->get();

    $filters = [
        'search' => $search, // Use the $search variable you've already set
    ];

    return Inertia::render('Admin/Channels/Index', [
        'externalSources' => $externalSources, // Pass the fetched mistStreams directly
        'externalSourcesSearchFilters' => $filters,
    ]);

  }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
      $externalSources = ChannelExternalSource::all();
      return response()->json($externalSources);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
