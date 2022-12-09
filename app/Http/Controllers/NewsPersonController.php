<?php

namespace App\Http\Controllers;

use App\Models\NewsPerson;
use Illuminate\Http\Request;

class NewsPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'id' => 'required',
        ]);
        NewsPerson::create([
            'user_id' => $request->id,
        ]);
        return redirect()->route('newsroom')->with('message', $request->name . ' has successfully been added to the Newsroom.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Http\Response
     */
    public function show(NewsPerson $newsPerson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Http\Response
     */
    public function edit(NewsPerson $newsPerson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewsPerson $newsPerson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsPerson  $newsPerson
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsPerson $newsPerson)
    {
        //
    }
}
