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

	/**
	 * its neccesary to have a mapping of parameters
	 * for each resource as the param keys might be
	 * different
	 *
	 */
	protected $paramsMapping = [
		'year'          => 'launch_year',
		'flight_number' => 'flight_number',
		'limit'         => 'limit',
		'offset'        => 'offset',
		'sort'          => 'sort',
		'order'         => 'order',
	];

	/**
	 * this holds the key of guzzle request params
	 * can be either body|json|query
	 */
	protected $paramType = "query";

	/**
	 * this is the concrete implementation on the abstract method in parent class
	 *
	 * @param array $data
	 * @return collection LaunchCollection
	 */
	protected function makeCollection(array $data): LaunchCollection
	{
		return new LaunchCollection(collect($data));
	}
}