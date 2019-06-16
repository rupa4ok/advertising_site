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
		    return $user->isAdmin() || $user->isModerator();
	    });
	    Gate::define('manage-pages', function (User $user) {
		    return $user->isAdmin();
	    });
	    Gate::define('manage-users', function (User $user) {
		    return $user->isAdmin() || $user->isModerator();
	    });
	    Gate::define('manage-tickets', function (User $user) {
		    return $user->isAdmin() || $user->isModerator();
	    });
	    Gate::define('manage-regions', function (User $user) {
		    return $user->isAdmin();
	    });
	    Gate::define('manage-adverts', function (User $user) {
		    return $user->isAdmin() || $user->isModerator();
	    });
	    Gate::define('manage-adverts-categories', function (User $user) {
		    return $user->isAdmin() || $user->isModerator();
	    });
	    Gate::define('manage-banners', function (User $user) {
		    return $user->isAdmin() || $user->isModerator();
	    });
	    Gate::define('show-advert', function (User $user, Advert $advert) {
		    return $user->isAdmin() || $user->isModerator() || $advert->user_id === $user->id;
	    });
	    Gate::define('manage-own-advert', function (User $user, Advert $advert) {
		    return $advert->user_id === $user->id;
	    });
	    Gate::define('manage-own-banner', function (User $user, Banner $banner) {
		    return $banner->user_id === $user->id;
	    });
	    Gate::define('manage-own-ticket', function (User $user, Ticket $ticket) {
		    return $ticket->user_id === $user->id;
	    });
    }
}
