<?php

namespace App\Services;

use App\Http\Resources\Launch as LaunchResource;
use App\Http\Resources\LaunchCollection;

class SpaceXDataService extends AbstractRemoteSourceService implements Interfaces\RemoteSourceInterface
{
	const BASEURL = "https://api.spacexdata.com/";
	const VERSION = "v2";

	protected $baseUrl = self::BASEURL;
	protected $uri = '/' . self::VERSION . self:: ENDPOINT;
	protected $resource = 'App\Http\Resources\Launch';
	protected $collection = 'App\Http\Resources\LaunchCollection';

	/**
	 * Since we are hiting only one specific endpoint
	 * and retriving only one type of collection i am hardcoding it in a const.
	 * If we give the option to retrive a variety of collections we could specify
	 * it in a req param 'collection'.This would be used in the guzzle req uri as endpoint
	 * or could be used to fetch the enpoint for that collection from a config/mapping file.
	 * This is just a naive solution as i am in tight work schedule.
	 *
	 */
	const ENDPOINT = "/launches";

	protected $paramsMapping = [
		'year' => 'launch_year',
		'flight_number' => 'flight_number',
		'limit' => 'limit',
		'offset' => 'offset'
	];

	protected $paramType = "query";
}