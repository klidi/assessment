<?php

namespace App\Services\Traits;

use GuzzleHttp\Client;

trait GuzzleTrait
{
	/**
	 * Initialize guzzle client
	 *
	 * @return GuzzleHttp\Client
	 */
	protected function initializeClient()
	{
		return new Client([
		    'base_uri' => $this->baseUrl,
		]);
	}

	/**
	 * Makes guzzle request
	 *
	 * @param  string $method
	 * @param  string $uri
	 * @param  array $options
	 * @param  array $uriParams
	 * @return GuzzleHttp\Client
	 */
	protected function makeCall(string $method, string $uri, array $options, array $uriParams): array
	{
		$url = $uri;
		if (!empty($uriParams)) {
			$url = $this->prepareUrl($uriParams);
		}

		try {
	        $response = $this->client->$method($url, $options);
	       	return json_decode($response->getBody()->getContents());
	    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
    	    if ($e->hasResponse()) {
    	    	// simple logging nothing fancy
		        Log::error(Psr7\str($e->getResponse()));
		    }
	    }
	}

	/**
	 * Prepare guzzle url by replacing uri varibles/params
	 *
	 * @param  array $uriParams
	 * @return string
	 */
	protected function prepareUrl(array $uriParams): string
	{
		return \GuzzleHttp\uri_template($this->baseUrl, $uriParams);
	}
}