<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
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
//        Validator::make($request, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => $this->passwordRules(),
//        ])->validate();
//
//        return User::create([
//            'name' => $request['name'],
//            'email' => $request['email'],
//            'password' => Hash::make($request['password']),
//            'role_id' => '2',
//            'user_address_1' => null,
//            'user_address_2' => null,
//            'user_address_city' => null,
//            'user_address_province' => null,
//            'user_address_country' => null,
//            'user_address_postal_code' => null,
//            'user_phone' => null,
//            'creator_number' => null,
//            'subscription_status' => null,
//        ]);

        $attributes = Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role_id',
            'user_address_1',
            'user_address_2',
            'user_city',
            'user_province',
            'user_country',
            'user_postal_code',
            'user_phone',
        ]);
        // create the user
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'role_id' => $request['role_id'],
            'user_address_1' => $request['user_address_1'],
            'user_address_2' => $request['user_address_2'],
            'user_address_city' => $request['user_address_city'],
            'user_address_province' => $request['user_address_province'],
            'user_address_country' => $request['user_address_country'],
            'user_address_postal_code' => $request['user_address_postal_code'],
            'user_phone' => $request['user_phone'],
            'creator_number' => null,
            'subscription_status' => null,
        ]);
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
