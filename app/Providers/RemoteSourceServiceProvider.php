<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class RemoteSourceServiceProvider extends ServiceProvider
{
    /**
     * Here we hold the list of avaiable services.
     * List can be extended as out services grow
     *
     *@var array
     */
    private $avaiableSources = [
        'space' => '\App\Services\SpaceXDataService',
        'comics' => '\App\Services\XkcdService',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('App\Services\Interfaces\RemoteSourceInterface', function() {
            $request = app(\App\Http\Requests\BaseRequest::class);
            $service = $this->avaiableSources[$request->get('sourceId')];
            return new $service;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
