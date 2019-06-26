<?php

namespace App\Providers;

use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class RemoteSourceServiceProvider extends ServiceProvider
{
    /**
     * Here we hold the list of avaiable services.
     * List can be extended as out services grow
     * we can place this list also in the config to
     * keep the service provider clean
     *
     * @var array
     */
    private $avaiableSources = [
        'space'  => '\App\Services\SpaceXDataService',
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
            if (isset($this->avaiableSources[$request->get('sourceId')])) {
                $service = $this->avaiableSources[$request->get('sourceId')];
                return new $service;
            }
            throw new HttpException(404);
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
