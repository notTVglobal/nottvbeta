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
        return Inertia::render('Users/Index', [
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
        return Inertia::render('Users/Create');
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
            'address_1',
            'address_2',
            'city',
            'province',
            'country',
            'postal_code',
            'phone',
        ]);
        // create the user
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'role_id' => $request['role_id'],
            'address_1' => $request['address_1'],
            'address_2' => $request['address_2'],
            'city' => $request['city'],
            'province' => $request['province'],
            'country' => $request['country'],
            'postal_code' => $request['postal_code'],
            'phone' => $request['user_phone'],
            'creator_number' => null,
            'subscription_status' => null,
        ]);
        // redirect
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return Inertia::render('Users/{$id}/Index', [
            'userSelected' => $user,
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
        return Inertia::render('Users/{$id}/Edit', [
            'userEdit' => $user,
//            'role_id' => $user->role_id,
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

        // validate the request
        $attributes = Request::validate([
            'id',
            'name',
            'email',
            'address_1',
            'address_2',
            'city',
            'province',
            'country',
            'postal_code',
            'phone',
            'role_id'
        ]);

        // update the user
//        User::where('id', $user)->update($attributes);
        $user->update($attributes);
//        $user>save($attributes);
//        sleep(1);

        // redirect
        return Inertia::render('Users/{$id}/Index', [
            'userSelected' => $user
        ])->with('message', 'User Updated Successfully');


//        $request->validate([
//            'name' => 'required',
//            'email' => 'required',
//            'address_1',
//            'address_2',
//            'city',
//            'province',
//            'country',
//            'postal_code',
//            'phone',
//            'role_id',
//        ]);
//
//        $user->name = $request->name;
//        $user->email = $request->email;
//        $user->address_1 = $request->address_1;
//        $user->address_2 = $request->address_2;
//        $user->city = $request->city;
//        $user->province = $request->province;
//        $user->country = $request->country;
//        $user->postal_code = $request->postal_code;
//        $user->phone = $request->phone;
//        $user->role_id = $request->role_id;
//
//        $user->save();
//        sleep(1);
//
//        return redirect()->route('admin.users')->with('message', 'Blog Updated Successfully');

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
