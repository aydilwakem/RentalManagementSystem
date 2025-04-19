<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Property;
use App\Models\Room;
use App\Models\EventHall;

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
    public function boot()
    {
        Relation::morphMap([
            1 => \App\Models\Property::class,
            2 => \App\Models\Room::class,
            3 => \App\Models\EventHall::class,
        ]);
    }
}
