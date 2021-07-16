<?php

namespace App\Providers;

use App\Service\Pushall;
use Illuminate\Support\ServiceProvider;

class PushAllServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Pushall::class, function() {
            return new Pushall(config('kirill.pushall.api.key'), config('kirill.pushall.api.id'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
