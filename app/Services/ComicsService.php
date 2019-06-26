<?php

namespace App\Services;

use App\Http\Resources\Comics as ComicsResource;
use App\Http\Resources\ComicsCollection;

class ComicsService extends AbstractRemoteSourceService implements Interfaces\RemoteSourceInterface
{
	const BASEURL = "";
	const VERSION = "";

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
	 * @var string
	 *
	 */
	const ENDPOINT = "";

	/**
	 * Mapping our params to the remote resource params
	 * its neccessary for every service since we might have
	 * differences in the key names
	 *
	 * @var array
	 *
	 */
	protected $paramsMapping = [
		'year'   => 'launch_year',
		'limit'  => 'limit',
		'offset' => 'offset'
	];

	/**
	 * Here we indicate of the params should be send in query
	 * or as uri params
	 *
	 * @var string query|uri
	 *
	 */
	protected $paramType = "query";

	/**
	 * Concrete implementation of the abstract method
	 *
	 * @param array
	 *
	 */
	protected function makeCollection(array $data): ComicsCollection
	{
		return new ComicsCollection(collect($data));
	}
}