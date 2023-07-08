<?php

namespace App\Providers;

use Laravel\Passport\Passport; 

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // client's secrets to be hashed when stored in database
        // Passport::hashClientSecrets();
        // token lifetime setup
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(1));
        Passport::personalAccessTokensExpireIn(now()->addDays(1));
    }
}
