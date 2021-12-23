<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('nearestSmallerOf', function ($count) {
            return $this->keys()->sort()->reverse()->first(
                function ($value, $key) use ($count) {
                    return $value <= $count;
                }
            );
        });
    }
}
