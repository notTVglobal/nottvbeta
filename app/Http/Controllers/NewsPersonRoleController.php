<?php

namespace App\Http\Controllers;

use App\Models\NewsPersonRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsPersonRoleController extends Controller
{
  /**
   * Show the list of all News Person Roles.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse {
    $roles = NewsPersonRole::all();
    return response()->json($roles);
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
     * @param  \App\Models\NewsPersonRole  $newsPersonRole
     * @return \Illuminate\Http\Response
     */
    public function show(NewsPersonRole $newsPersonRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsPersonRole  $newsPersonRole
     * @return \Illuminate\Http\Response
     */
    public function edit(NewsPersonRole $newsPersonRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NewsPersonRole  $newsPersonRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewsPersonRole $newsPersonRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsPersonRole  $newsPersonRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsPersonRole $newsPersonRole)
    {
        //
    }
}
