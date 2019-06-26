## About
E******* - assessment (RESTful API)

The API backend will have to connect to one or more remote sources, retrieve its data through an appropriate connector module and transform it accordingly before outputting it.

Project is build with Laravel 5.8, i had a choice to make in this matter but due to my time beeing very very limited i picked Laravel instead of Lumen. I thought also to do something in Phoenix(elixir/erlang) just to show a different aproach with a different paradigm but again my time is very very limited. (Some devs are on vacation hole month and my parents are here to visit us since last week so between all this and kid leaves me almost no time for this)

## Requirements Installation

PHP >= 7.1.3	

NOTE: Redis is used as a cache, default config uses a local redis instance.

In case you dont have a local redis :  provide a remote redis host or switch the cache driver to file or any other avaiable driver.

To switch the default cache driver go to config/cache.php:

	'default' => env('CACHE_DRIVER', 'file'),

	 composer install

## Usage

assessment.local/api/?sourceId=space&year=2013&limit=2

The index route is only accepting GET method though from the examples looked like a POST request i did not feel right about it.

The api accepts other parameters like offset, order, sort. P.s offset does not give the expected result from spaceX api.

On the request/response examples the comics response is a spacex response , from there i can not assume what properties will go in the response and also what parameters will be used for that resource. Also by looking at comics api does not help in this matter. I have prepared the infrastructure to plug it in and stoped there.

In the controller index Action:

	public function index(RemoteSourceInterface $remoteResource, BaseRequest $request)
	{
	    return response()->json($remoteResource->fetch($request->all()));
	}

The RemoteResourceInterface is implemented by an Abstract class which serves a parent class to all the remote services we
are going to plug in

	abstract class AbstractRemoteSourceService implements Interfaces\RemoteSourceInterface
	{
	}

And then in the concrete services we have:

	class SpaceXDataService extends AbstractRemoteSourceService implements Interfaces\RemoteSourceInterface
	{
	}

The logic for registering the right service based on resourceId stands in the RemoteSourceServiceProvider

Here we define the avaiable services

	private $avaiableSources = [
        'space'  => '\App\Services\SpaceXDataService',
        'comics' => '\App\Services\XkcdService',
    ];

And this is how we register it

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

The solution is very simple, in case of extensive amount of pluged in services we can move $avaiableSource in the config file




