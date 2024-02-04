<?php

namespace App\Http\Controllers;

use App\Models\ShowSchedule;
use Illuminate\Http\Request;

class ShowScheduleController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
      // Fetch the current schedule for the next 6 hours for 5 days.
      return ShowSchedule::with(['show', 'mistStream', 'showEpisode', 'movie', 'movieTrailer'])->get();
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
        // this is where we create a new showSchedule object
        // from a show (with an associated mist_stream or showEpisode),
        // movie, or movieTrailer... and in due time a promo, ad, psa,
        // station id, interstitial, or filler.
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShowSchedule  $showSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(ShowSchedule $showSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShowSchedule  $showSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(ShowSchedule $showSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShowSchedule  $showSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShowSchedule $showSchedule)
    {
        // we receive the id of the showSchedule item in the request to update.
        // the time slot can either change from a show vod (show_episode_id) to
        // a show livestream (mist_stream_id) or to a movie or a trailer (movie_trailer_id)
        // or change its start/end times,

      // Update the schedule with new show placements, handling drag-and-drop inputs.
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShowSchedule  $showSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShowSchedule $showSchedule)
    {
        //
    }
}
