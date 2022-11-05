<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\ShowPolicy;
use App\Policies\TeamPolicy;
use App\Models\User;
use App\Policies\Creator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
        Team::class => TeamPolicy::class,
        Creator::class => CreatorPolicy::class,
        Show::class => ShowPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
