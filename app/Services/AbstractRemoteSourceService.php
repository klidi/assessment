<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Services\Traits\GuzzleTrait;
use Illuminate\Support\Collection;

abstract class AbstractRemoteSourceService implements Interfaces\RemoteSourceInterface
{
	protected $defaultHeaders = [
		'Content-Type' => 'application/json',
	];

	protected $uriParams = [];

	use Traits\GuzzleTrait;

	public function fetch(array $params): ResourceCollection
	{
		$options = $this->prepareParams($params);
		$options['headers'] = $this->defaultHeaders;

		$content = $this->submit('get', $this->uri, $options, $this->uriParams);

		$data = collect($content)->map(function($row){
		      return $this->resource::make($row)->resolve();
		});
		
		return new $this->collection($data);
	}

	protected function prepareParams(array $params): array
	{
		unset($params['sourceId']);

		$options = [];
		foreach ($params as $key => $value) {
			$options[$this->paramType][$this->paramsMapping[$key]] = $value;
		}

		return $options;
	}
}