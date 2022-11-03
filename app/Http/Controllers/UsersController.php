<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
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
        function role($roleId) {
            $role = Role::query()->where('id', $roleId)->first();
            return $role->role;
        }
//        $teams = User::find(1)->teams()->orderBy('name')->get();
//        dd($teams);

//        foreach ($user->teams as $team) {
//            return $team->pivot->created_at;
//        }

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
                    'email' => $user->email,
                    'profile_photo_path' => $user->profile_photo_path,
                    'role' => role($user->role_id),
                    'isAdmin' => $user->isAdmin,
                    'can' => [
                        'edit' => Auth::user()->can('edit', $user)
                    ],
                    'userSelected' => $user,
                ]),
            'filters' => Request::only(['search']),
            'can' => [
                'viewAnyUser' => Auth::user()->can('viewAny', User::class),
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
    public function store(User $user, Request $request)
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
//            'address1' => null,
//            'address2' => null,
//            'city' => null,
//            'province' => null,
//            'country' => null,
//            'postalCode' => null,
//            'phone' => null,
//            'creatorNumber' => null,
//            'subscriptionStatus' => null,
//        ]);

        $attributes = Request::validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'string',
            'role_id' => 'integer',
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postalCode' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);
        // create the user
        User::create($attributes);
        return redirect('/users')->with('message', 'User Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        function role($roleId) {
            $role = Role::query()->where('id', $roleId)->first();
            return $role->role;
        }
        return Inertia::render('Users/{$id}/Index', [
            'userSelected' => $user,
            'role' => role($user->role_id),
            'teams' => $user->teams,
            'can' => [
                'viewAnyUser' => Auth::user()->can('viewAny', User::class),
                'createUser' => Auth::user()->can('create', User::class),
                'editUser' => Auth::user()->can('edit', User::class)
            ]
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
        // validate the request
        $attributes = Request::validate([
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postalCode' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'role_id' => 'integer',
        ]);

        // update the user

        $user->update($attributes);
        sleep(1);

        // redirect
        return Inertia::render('Users/{$id}/Index', [
            'userSelected' => $user
        ])->with('message', 'User Updated Successfully');
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
