<?php

namespace App\Providers;

use App\Models\Adverts\Advert\Advert;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];
    
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin-panel', function (User $user) {
            return $user->isAdmin();
        });
	
	    Gate::define('moderate-advert', function (User $user) {
		    return $user->isAdmin();
	    });
	
	    Gate::define('show-advert', function (User $user, Advert $advert) {
		    return $user->isAdmin() || $user->isModerator() || $advert->user_id == $user->id;
	    });
	
	    Gate::define('edit-own-advert', function (User $user, Advert $advert) {
		    return $advert->user_id === $user->id;
	    });
    }
}
