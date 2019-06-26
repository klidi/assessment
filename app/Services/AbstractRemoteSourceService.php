<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use App\Services\Traits\GuzzleTrait;
use Illuminate\Support\Collection;

abstract class AbstractRemoteSourceService implements Interfaces\RemoteSourceInterface
{

	/*
	 * I am caching the collection for one hr
	 * even though the data is static and not prone to changes
	 * and we could cache it for long periods i dont wont to use memory or space in our servers
	 * caching for a short period is enough to not bloat the external api servers or hit some rate limiting
	 *
	 */
	const CACHE_TIME = 3600;

	/**
	 * default key for guzzle request params
	 * can be either body|json|query and can be overwritten
	 * in child classes
	 */
	protected $paramType = "query";

	/**
	 * Default guzzle request headers, can be overwritten
	 * in child classes
	 */
	protected $defaultHeaders = [
		'Content-Type' => 'application/json',
	];

	/**
	 * holds instance of guzzle client
	 */
	protected $client;

	/*
	 * this property holds the uri params
	 * and is passed as argument to prepareUrl() in the guzzle trait
	 * can be overwritten in concrete service classes
	 */
	protected $uriParams = [];

	use Traits\GuzzleTrait;

	public function __construct()
	{
		$this->client = $this->initializeClient();
	}

	/**
	 * fetch collection from api/cache
	 *
	 * @param array $params
	 */
	public function fetch(array $params): ResourceCollection
	{

		/**
		 * i am using the Cache facade and not redis facade
		 * in case later we might need to change the cache driver
		 *
		 * our use cases are simple but in case we would have extensive and complex usage of cache i would create a cache
		 * service class and move all related code in it to not bloat our class
		 */
		if (Cache::has($this->prepareCacheKey($params))) {
		    return Cache::get($this->prepareCacheKey($params));
		} else {
    		$options            = $this->prepareParams($params);
    		$options['headers'] = $this->defaultHeaders;

			$content    = $this->makeCall('get', $this->uri, $options, $this->uriParams);
			$collection = $this->makeCollection($content);

			Cache::put($this->prepareCacheKey($params), $collection, self::CACHE_TIME);
		}

		return $this->makeCollection($content);
	}

	/**
	 * prepare request params for guzzle request
	 * this parameters go either as query or json
	 * depending on api. $this->paramType is defined in the concrete service
	 *
	 * @param array $params
	 * @return array
	 */
	protected function prepareParams(array $params): array
	{
		unset($params['sourceId']);

		$options = [];
		foreach ($params as $key => $value) {
			$options[$this->paramType][$this->paramsMapping[$key]] = $value;
		}

		return $options;
	}

	/**
	 * prepare cache key by imploding request params
	 * this way we ensure that key is unique and we easely fatch
	 * the data in the upcoming requests
	 *
	 * @param array $params
	 * @return string
	 */
	protected function prepareCacheKey(array $params): string
	{
		return implode(':', $params);
	}

	/**
	 * this method has to be implemented in the concrete service classes
	 * cuz every service has its own resource and resource collection classes
	 *
	 * @param array $data
	 */
	abstract protected function makeCollection(array $data);
}