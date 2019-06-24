<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Resources\Json\ResourceCollection;

interface RemoteSourceInterface
{
	public function fetch(array $params): ResourceCollection;
}