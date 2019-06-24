<?php

namespace App\Services\Traits;

use GuzzleHttp\Client;

trait GuzzleTrait
{

	protected function initializeClient()
	{
		return new Client([
		    'base_uri' => $this->baseUrl,
		    'verify' => false
		]);
	}

	protected function submit($method, $uri, $options, $uriParams)
	{
		$client = $this->initializeClient();

		try {
	        $response = $client->$method($uri, $options);
	       	return json_decode($response->getBody()->getContents());
	    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
    	    if ($e->hasResponse()) {
    	    	// simple logging nothing fancy
		        Log::error(Psr7\str($e->getResponse()));
		    }
	    }
	}

	protected function prepareUrl(array $uriParams): string
	{
		return \GuzzleHttp\uri_template($this->baseUrl, $uriParams);
	}
}