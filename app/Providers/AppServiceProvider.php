<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Property;
use App\Models\Room;
use App\Models\EventHall;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    /**
     * Superadmin Gate for all permissions
     */
    public function boot()
    {
         Gate::before(function ($user, $ability) {
        return $user->hasRole('Super Admin') ? true : null;
    });

        Relation::morphMap([
            1 => \App\Models\Property::class,
            2 => \App\Models\Room::class,
            3 => \App\Models\EventHall::class,
        ]);




    }
}
