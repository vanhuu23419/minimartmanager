<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Auth\RoleClaims;
use App\Http\Requests\Auth\RoleClaims\AdminRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
