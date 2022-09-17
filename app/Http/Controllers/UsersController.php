<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role_id' => $user->role_id,
                    'isAdmin' => $user->isAdmin,
                    'can' => [
                        'edit' => Auth::user()->can('edit', $user)
                    ]
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewAllUsers' => Auth::user()->can('viewAll', User::class),
                'createUser' => Auth::user()->can('create', User::class),
                'editUser' => Auth::user()->can('edit', User::class)
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);
        // create the user
        User::create($attributes);
        // redirect
        return redirect('/admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'role_id' => $user->role_id,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'role_id' => $user->role_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
//        $attributes = Request::validate([
//            'id',
//            'profile_photo_url',
//            'name' => 'required',
//            'email' => ['required', 'email'],
//        ]);
//        // update the user
//        User::where('id', $user)->update($attributes);
//        // redirect
//        return redirect('/admin/users')
//            ->with ('message', 'User updated succesfully');
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
