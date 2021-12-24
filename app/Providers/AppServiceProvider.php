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
        # this is to access properties with dot notation
        Collection::macro('pick', function ($key) {
            return data_get($this, $key);
        });

        # find nearest elements
        Collection::macro('nearest', function (int $count, string $comparison) {
            $data = $this->keys()->sort();

            if ($comparison == 'smaller') {
                $data = $data->reverse();
            }

            return $data->first(
                function ($value, $key) use ($count, $comparison) {
                    return $comparison == 'smaller' ? $value <= $count : $value > $count;
                }
            );
        });

        # filter collection by keys
        Collection::macro('keysUntil', function ($count) {
            return $this->keys()->sort()->filter(
                function ($key) use ($count) {
                    return $key <= $count;
                }
            );
        });
    }
}
