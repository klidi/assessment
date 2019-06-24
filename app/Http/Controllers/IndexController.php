<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\RemoteSourceInterface;
use App\Http\Requests\BaseRequest;

class IndexController extends Controller
{
    public function index(RemoteSourceInterface $remoteResource, BaseRequest $request)
    {
        return $remoteResource->fetch($request->all());
    }
}
